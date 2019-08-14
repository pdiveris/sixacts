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
     * Please consult the Wiki page for an explanation of how voting works
     *
     * @link https://github.com/pdiveris/sixacts/wiki/Voting,-the-ruleset
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws \Exception
     */
    public function vote(Request $request)
    {
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
        
        // this needs changing to be set from the session or from token
        $user = User::find((int)$params['user']['user']);

        // if not user etc..
        if (!$user) {
            return response()->json(['type' => 'error', 'message' => 'You need to be logged in to vote']);
        }

        if (!$vote) {
            $vote = new \App\Vote();
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
    
    public function testa(Request $request)
    {
        $q = '';

        $query = <<<EOT
 proposals.id as id,
    proposals.title as title,
    proposals.body as body,
    proposals.slug as slug,
    MATCH (proposals.title, proposals.body)
    AGAINST ('*ind*' IN BOOLEAN MODE) as score,
    category.sub_class as category_sub_class,
    category.short_title as category_short_title,
    user.display_name as user_display_name,
    user.name as user_name,
    aggs.total_votes as aggs_total_votes
    FROM proposals_view proposals
    LEFT JOIN categories category ON (category.id = proposals.category_id)
    LEFT JOIN users user ON (user.id = proposals.user_id)
    LEFT JOIN vote_aggs aggs ON (proposals.id = aggs.proposal_id)
    WHERE category_id in (1,2,3,4,5,6)
    ORDER BY score DESC, category_id ASC
    ;

EOT;
        $props = ProposalView::selectRaw($query, [$q])->first();
        
        // $props = ProposalView::where()
        
        krumo($props->getAttributes());


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
        $q = $request->get('q', '');
        $cats_replacement = '';
        
        $query = <<<EOT
 proposals.id as id,
    proposals.title as title,
    proposals.body as body,
    proposals.slug as slug,
    MATCH (proposals.title, proposals.body)
    AGAINST ('*?*' IN BOOLEAN MODE) as score,
    category.sub_class as category_sub_class,
    category.class as category_class,
    category.short_title as category_short_title,
    user.display_name as user_display_name,
    user.name as user_name,
    aggs.total_votes as aggs_total_votes,
    aggs.total_dislikes as aggs_total_dislikes
    FROM proposals_view proposals
    LEFT JOIN categories category ON (category.id = proposals.category_id)
    LEFT JOIN users user ON (user.id = proposals.user_id)
    LEFT JOIN vote_aggs aggs ON (proposals.id = aggs.proposal_id)
    #cats_replacement
    #order_by
    ;
EOT;
        
        if (null !== $catsQuery && '' !== $catsQuery) {
            // $cats = explode(':', $catsQuery);
            $cats = str_replace(':', ',', $catsQuery);
            $query = str_replace(
                ['#cats_replacement', '#order_by'],
                ["WHERE category_id  IN ($cats)", "ORDER BY score DESC"],
                $query
            );
            
            // $props = ProposalView::whereIn('category_id', $cats)->get();
            $props = ProposalView::selectRaw($query, [$q])->get();
        } else {
            $query = str_replace(array('#cats_replacement', '*?*'), '', $query);
            $filter = $request->get('filter', '');
            switch ($filter) {
                case 'most':
                    $query = str_replace('#order_by', 'ORDER BY num_votes DESC, score DESC', $query);
                    // $props = \App\ProposalView::orderBy('num_votes','desc')->get();
                    $props = ProposalView::selectRaw($query, [$q])->get();
                    break;
                case 'recent':
                    $query = str_replace('#order_by', 'ORDER BY created_at DESC, score DESC', $query);
                    // $props = \App\ProposalView::orderBy('created_at','desc')->get();
                    $props = ProposalView::selectRaw($query, [$q])->get();
                    break;
                case 'current':
                    $query = str_replace('#order_by', 'ORDER BY num_votes DESC, score DESC', $query);
                    // $props = \App\ProposalView::orderBy('num_votes','desc')->get();
                    $props = ProposalView::selectRaw($query, [$q])->limit(6);
                    break;
                default:
                    // $props = \App\ProposalView::all();
                    $query = str_replace('#order_by', 'ORDER BY num_votes DESC, score DESC', $query);
                    // $props = \App\ProposalView::orderBy('num_votes','desc')->get();
                    $props = ProposalView::selectRaw($query, [$q])->get();
                    
            }
            // move code to getFiltered
            // $props = ProposalView::getFiltered($filter);
            // dump($filter);
            dump($query);
            // dump($props);
        }
        if ($userId > 0) {
            $props = StaticController::mergeProposalsWithVotes($props, $userId);
        }
        foreach ($props as $prop) {
            $prop->display = 'collapsed';
/*            if ($prop->category) {
                $prop->hasCategory = true;
            }
            if (count($prop->aggs)>0) {
                $prop->hasAggs = true;
            }
            if ($prop->user) {
                $prop->hasUser = true;
            }*/
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
