<?php

namespace App\Http\Controllers;

use App\Events\MessagePosted;
use App\Models\Proposal;
use App\Models\ProposalView;
use App\Models\User;
use App\Models\Vote;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class ProposalController
 *
 * @category Auth
 * @package  App\Http\Controllers
 * @author   Petros Diveris <petros@diveris.org>
 * @license  Apache 2.0
 * @link     https://www.diveris.org
 */
class ProposalController extends Controller
{
    /**
     * Vote action
     * Please consult the Wiki page for an explanation of how voting works
     *
     * @link https://github.com/pdiveris/sixacts/wiki/Voting,-the-ruleset
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function vote(Request $request): JsonResponse
    {
        $pause = \Cache::get('pause', 'off');

        if ($pause === 'on') {
            return response()->json([
                'type' => 'warning',
                'message' => 'Voting is paused for 30 minutes!'
            ]);
        }
        $params = $request->all();
        $response = ['action'=>'unspecified'];

        if (!array_key_exists('proposal_id', $params) ||
            !array_key_exists('direction', $params) ||
            !array_key_exists('user', $params)
        ) {
            return response()->json(['type' => 'error', 'message' => 'Bad request (missing data)']);
        }

        if (!($params['direction'] === 'vote') && !($params['direction'] === 'dislike')) {
            return response()->json(['type' => 'error', 'message' => 'Bad request (vote)']);
        }

        $vote = Vote::where('user_id', '=', $params['user']['user'])
            ->where('proposal_id', '=', $params['proposal_id'])
            ->first()
        ;

        $user = User::find((int)\Auth::user()->id);

        // if not user etc..
        if (!$user) {
            return response()->json(['type' => 'error', 'message' => 'You need to be logged in to vote']);
        }

        if (!$vote) {
            $vote = new \App\Models\Vote();
            $vote->user_id = $params['user']['user'];
            $vote->proposal_id = $params['proposal_id'];

            if ($params['direction'] === 'dislike') {
                $vote->dislike = 1;
                $response = [
                    'type' => 'success',
                    'message' => 'Your dislike has been added',
                    'action' => 'persist'
                ];
            } else {
                $vote->vote = 1;
                $response = [
                    'type' => 'success',
                    'message' => 'Your vote has been added',
                    'action' => 'persist'
                ];
            }
        } else {
            if ((int)$vote->vote === 1 && (int)$vote->dislike === 1) {
                if ($params['direction'] === 'dislike') {
                    $vote->dislike = 0;
                    $response = [
                        'type' => 'success',
                        'message' => 'Your dislike has been removed',
                        'action' => 'persist'
                    ];
                } else {
                    $response = [
                        'type' => 'success',
                        'message' => 'Your vote was removed',
                        'action' => 'persist'
                    ];
                    $vote->vote = 0;
                }
            } elseif ((int)$vote->vote === 1 && (int)$vote->dislike === 0) {
                if ($params['direction'] === 'dislike') {
                    $vote->dislike = 1;
                    $response = [
                        'type' => 'success',
                        'message' => 'Your dislike has been added',
                        'action' => 'persist'
                    ];
                } else {
                    $response = [
                        'type' => 'success',
                        'message' => 'Your vote was removed',
                        'action' => 'persist'
                    ];
                    $vote->vote = 0;
                }
            } elseif ((int)$vote->vote === 0 && (int)$vote->dislike === 0) {
                if ($params['direction'] === 'dislike') {
                    $vote->dislike = 1;
                    $response = [
                        'type' => 'success',
                        'message' => 'Your dislike has been added',
                        'action' => 'persist'
                    ];
                } else {
                    $vote->vote = 1;
                    $response = [
                        'type' => 'success',
                        'message' => 'Your vote has been added',
                        'action' => 'persist'
                    ];
                }
            } elseif ((int)$vote->vote === 0 && (int)$vote->dislike === 1) {
                if ($params['direction'] === 'dislike') {
                    $response = [
                        'type' => 'success',
                        'message' => 'Your dislike has been removed',
                        'action' => 'remove'
                    ];
                } else {
                    $vote->vote = 1;
                    $response = [
                        'type' => 'success',
                        'message' => 'Your vote has been added',
                        'action' => 'persist'
                    ];
                }
            }
        }
        if ($response['action'] === 'remove') {
            $ret = $vote->delete();
        } elseif ($response['action'] === 'persist') {
            $ret = $vote->save();
        } elseif ($response['action'] === 'discard') {
            $ret = true;
        } else { // Horror!
            return response()->json(['type' => 'error', 'message' => 'HORROR!']);
        }

        if ($ret) {
            if (env('SOCKET_PROVIDER', 'echo') === 'nchan') {
                event(
                    new \App\Events\ProposalVotedEvent([
                        'message' => 'refresh', 'user' => $user->name
                    ],
                        'messages',
                        $user
                    )
                );
            } else {
                event(new MessagePosted($user, 'refresh'));
            }
            return response()->json($response);
        }
        return response()->json(['type'=>'error', 'message'=>"Can't persist vote"]);
    }

    /**
     * Get all proposals with their aggregations (if they have any..)
     *
     * endpoint: /api/proposals?cats=&user_id=1&filter=current&q=
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $userId = $request->get('user_id');
        $catsQuery = $request->get('cats', '');

        $q = $request->get('q', '');
        $filter = $request->get('filter', '');
        $props = ProposalView::getFiltered($catsQuery, $q, $userId, $filter);

        foreach ($props as $prop) {
            $prop->display = 'collapsed';
        }

        if ($userId > 0) {
            $props = StaticController::mergeProposalsWithVotes($props, $userId);
        }
        foreach ($props as $prop) {
            $prop->display = 'collapsed';
        }
        return response()->json($props);
    }

    /**
     * Find by ID and show a proposal and qhiq it
     *
     * @param int $proposalId
     *
     * @return JsonResponse
     */
    public function show(int $proposalId = 0): JsonResponse
    {
        $prop = Proposal::find((int)($proposalId));
        if (null===$prop) {
            return response()->json(['type'=>'error', 'error' => 'Not Found.'], 404);
        }
        if ($prop->category) {
            $prop->hasCategory = true;
        }
        if (count($prop->aggs) > 0) {
            $prop->hasAggs = true;
        }
        if ($prop->user) {
            $prop->hasUser = true;
        }
        return response()->json($prop);
    }

    public function view($id): Factory|View|Application
    {
        if (is_numeric($id)) {
            $proposal = ProposalView::find($id);
        } else {
            $proposal = ProposalView::where('slug', '=', $id)
                ->first();
        }

        if (! $proposal) {
            abort(521);
        }
        return view('static.proposal', ['proposal'=>$proposal]);
    }
}
