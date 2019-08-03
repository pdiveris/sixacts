<?php

namespace App\Http\Controllers;

use App\Events\MessagePosted;
use App\Proposal;
use App\ProposalView;
use App\User;
use App\Vote;
use Illuminate\Http\Request;

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
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws \Exception
     */
    public function vote(Request $request)
    {
        $params = $request->all();
        $response = [];
        if (!array_key_exists('proposal_id', $params) ||
            !array_key_exists('direction', $params) ||
            !array_key_exists('user', $params)
        ) {
            return response()->json(['type'=>'error', 'message'=>'Bad request (missing data)']);
        }
        if (!$params['direction'] == 'up' || !$params['direction'] == 'down') {
            return response()->json(['type'=>'error', 'message'=>'Bad request (vote)']);
        }
        
        $vote = Vote::where('user_id', '=', $params['user']['user'])
                        ->where('proposal_id', '=', $params['proposal_id'])
                        ->first();
        
        $user = User::find($params['user']['user']);
        // if not user etc..
        if (null===$user) {
            return response()->json(['type'=>'error', 'message'=>'You need to be logged in to vote']);
        }
        
        if (null===$vote) {
            dump('Voting fresh '.$params['direction']);
            $vote = new \App\Vote();
            $vote->user_id = $params['user']['user'];
            $vote->proposal_id = $params['proposal_id'];
            if ($params['direction'] === 'up') {
                $vote->vote = 1;
                $response = ['type' => 'success', 'message' => "Proposal voted " . $params['direction']];
            } elseif ($params['direction'] === 'dislike') {
                $vote->dislike = 1;
                $response = ['type' => 'success', 'message' => "Your dislike was registered"];
            }
            // $vote->save();
        } else {
            switch ($params['direction']) {
                case 'up':
                    if ($vote->vote > 0) {
                        $response =  [
                            'type'=>'warning',
                            'message' => 'You have already voted up this proposal'
                        ];
                    } else {
                        $vote->dislike = 0;
                        $vote->vote = 1;
                        $vote->save();
                        $response =  [
                            'type'=>'success',
                            'message' => 'Your vote has been castl'
                        ];
                    }
                    break;
                case 'down':
                    if ($vote->vote === 1) {
                        $vote->delete();
                        return response()->json([
                            'type'=>'success',
                            'message' => 'Your vote for this proposal has been removed'
                        ]);
                    }
                    dump('GROTESQUE POINT #1. ATTENTION! ATTENTION! ATTENTION! ');
// dislike
                    break;
                case 'dislike':
                    if ($vote->dislike > 0) {
                        $response = [
                            'type'=>'success',
                            'message' => 'You removed you dislike'
                        ];
                        // if vote == 0, delete, else decrease dislike
                        dump('The guy is removing negative vote, good on him!');
                    } else {
                        if ($vote->vote > 0) {
                            $response = [
                                'type'=>'warning',
                                'message' => 'You have disliked a proposal you have cast a vote for!'
                            ];
                            dump('ODD: disliking item he voted for..'.$vote->proposal_id);
                        } else {
                            dump('THIS SHOULD NEVER HAPPEN '.$vote->id);
                        }
                    }
                    
                    break;
            }
        }
        
        // $ret = $vote->save();
        $ret = true;
        
        if ($ret) {
            event(new MessagePosted($user, 'refresh'));
            return response()->json($response);
        }
        return response()->json(['type'=>'error', 'message'=>"Can't persist vote"]);
    }
    
    /**
     * Get all proposals with their aggregations (if they have any..)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $userId = $request->get('user_id');
        $catsQuery = $request->get('cats', '');
        if (null !== $catsQuery && '' !== $catsQuery) {
            $cats = explode(':', $catsQuery);
            $props = ProposalView::whereIn('category_id', $cats)->get();
        } else {
            $props = ProposalView::all();
        }
        if ($userId > 0) {
            $props = StaticController::mergeProposalsWithVotes($props, $userId);
        }
        foreach ($props as $prop) {
            $prop->display = 'collapsed';
            if ($prop->category) {
                $prop->hasCategory = true;
            }
            if (count($prop->aggs)>0) {
                $prop->hasAggs = true;
            }
            if ($prop->user) {
                $prop->hasUser = true;
            }
        }
        return response()->json($props);
    }
    
    /**
     * Find by ID and show a proposal and qhiq it
     *
     * @param int $proposalId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($proposalId = 0)
    {
        $prop = Proposal::find((int)($proposalId));
        if (null===$prop) {
            return response()->json(['type'=>'error', 'error' => 'Not Found.'], 404);
        }
        if ($prop->category) {
            $prop->hasCategory = true;
        }
        if (count($prop->aggs)>0) {
            $prop->hasAggs = true;
        }
        if ($prop->user) {
            $prop->hasUser = true;
        }
        return response()->json($prop);
    }
    
    public function view($id)
    {
        if (is_numeric($id)) {
            $proposal = ProposalView::find($id);
        } else {
            $proposal = ProposalView::where('slug', '=', $id)
                                        ->first();
        }
        
        if (null === $proposal || ! $proposal) {
            abort(521);
        }
        return view('static.proposal', ['proposal'=>$proposal]);
    }
}
