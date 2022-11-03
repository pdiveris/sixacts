<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Proposal;
use App\Models\ProposalView;
use App\Models\User;
use App\Models\Vote;
use GuzzleHttp\Client;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Psr\SimpleCache\InvalidArgumentException;

class StaticController extends Controller
{
    /**
     * Temporary main proposal content result set
     *
     * @param Request $request Request
     *
     * @return View
     */
    private $withErrors;

    public static $filterLabels = [
        'most'=>'Most Votes',
        'recent' => 'Most Recent',
        'current' => 'Current Document'
    ];

    public function content(Request $request): View
    {
        $proposals = Proposal::all();
        $categories = Category::all();

        $view = $request->path();
        return \View::make('static.'.$view, ['proposals'=>$proposals, 'categories'=>$categories]);
    }

    /**
     * Get a user's votes for a proposal, if any and add them to the recordset
     * @param $proposals
     * @param $userId
     * @return mixed
     */
    public static function mergeProposalsWithVotes($proposals, $userId): mixed
    {
        $votes = Vote::where('user_id', '=', (int)$userId)->get();
        foreach ($votes as $vote) {
            foreach ($proposals as $i => $prop) {
                if ((int)$prop->id === (int)$vote->proposal_id) {
                    $prop->myvote = [
                      'vote'=>$vote->vote,
                      'dislike'=>$vote->dislike
                    ];
                }
            }
        }
        return $proposals;
    }

    /**
     * Render the home view
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function homeRendered(Request $request): \Illuminate\View\View
    {
        $userId = $request->get('user_id');
        $catsQuery = $request->get('cats', '');
        $q = $request->get('q', '');
        $filter = $request->get('filter', '');


        $proposals = ProposalView::getFiltered($catsQuery, $q, $userId, $filter);
        $label = '';

        if (array_key_exists($filter, self::$filterLabels)) {
            $label = self::$filterLabels[$filter];
        }

        $categories = Category::all();
        $proposals = self::mergeProposalsWithVotes($proposals, $userId);
        return view(
            'static.ssr.welcome',
            [
                'filter' => $filter,
                'label'=>$label,
                'proposals'=>$proposals,
                'categories'=>$categories
            ]
        );
    }

    /**
     * Render the home view
     *
     * @return View
     */
    public function home(Request $request): View
    {
        $userId = $request->get('user_id');
        $catsQuery = $request->get('cats', '');
        $q = $request->get('q', '');
        $filter = $request->get('filter', '');
        $proposals = ProposalView::getFiltered($catsQuery, $q, $userId, $filter);

        $categories = Category::all();

        if (\Auth::user()) {
            $id = \Auth::user()->id;
        } else {
            $id = 0;
        }
        $id = \Auth::user() ? \Auth::user()->id : 0;
        $proposals = self::mergeProposalsWithVotes($proposals, $id);
        if (\Cache::get('ssr', false)) {
            return view(
                'static.ssr.welcome',
                ['proposals'=>$proposals, 'categories'=>$categories]
            );
        }

        return view(
            'static.welcome',
            ['proposals'=>$proposals, 'categories'=>$categories]
        );
    }

    /**
     * Render the home view
     *
     * @return View
     */
    public function nchan(): View
    {
        $proposals = ProposalView::all();
        $categories = Category::all();

        if (\Auth::user()) {
            $id = \Auth::user()->id;
        } else {
            $id = 0;
        }
        $id = \Auth::user() ? \Auth::user()->id : 0;
        $proposals = self::mergeProposalsWithVotes($proposals, $id);
        return view(
            'static.nchan',
            ['proposals'=>$proposals, 'categories'=>$categories]
        );

    }

    /**
     * Switch to SSR version and return
     * Home View
     *
     * @throws InvalidArgumentException
     */
    public function homePlain(Request $request): \Illuminate\View\View
    {
        \Cache::set('ssr', true);
        return $this->homeRendered($request);
    }

    /**
     * Handle non API proposal vote requests
     */
    public function plainVote(Request $request): RedirectResponse
    {
        if (!\Auth::user() || null == \Auth::user()) {
            return back()->withErrors(['error'=>'You must be logged in to vote']);
        }
        $user = \Auth::user();
        $scheme = $request->getScheme();
        $host = $request->getHost();

        $client = new Client(
            [
                "base_uri" => $scheme . '://' . $host,
                'verify' => false
            ]
        );

        $token = AuthController::getToken();

        $options = [
            'headers' =>
                [
                    'Authorization' => "Bearer {$token}"
                ],
            'json' => [
                "proposal_id" => (int)$request->get('pid', 0),
                "direction" => $request->get('action', 'unspecified'),
                "user" => ['user'=>$user->id ?? 0]
            ]
        ];
        $response = $client->post('/api/vote/', $options);

        // {"type":"success","message":"You've removed your vote","action":"persist"}
        // "type":"success","message":"Your vote has been added VOTE0DIS0","action":"persist"}
        $status = json_decode($response->getBody()->getContents());

        return back()->with(['type'=>$status->type, 'message' => $status->message]);
    }

    /**
     * Render the React view
     * It's the same as the home view usually but it serves as a testbed
     * for new features/fixes before they make their way to their actual place
     *
     * @return View
     */
    public function react(): View
    {
        $listData = json_encode(['Koko', 'Taylor', 'Tash', 'Μιχαλάκης', 'Lucia']);
        return view(
            'static.react',
            [
                'listData'=>$listData,
            ]
        );
    }

    /**
     * Compose the link to the author
     *
     * @param ProposalView $proposal proposal
     *
     * @return string
     */
    public static function authorLink(ProposalView $proposal)
    {
        $displayName = $proposal->user->display_name;
        if (null !== $displayName && '' !== $displayName) {
            return '@'.$proposal->user->display_name;
        }
        $names = explode(' ', $proposal->user->name);
        if (count($names) >= 2) {
            return '@'.
                strtolower(substr($names[0], 0, 1)).
                strtolower(substr($names[1], 0));
        } else {
            return '@'.strtolower($proposal->user->name);
        }
    }

    /**
     * Try local avatar, social avatar, gravatar
     *
     * @param User $user user
     *
     * @return string
     */
    public static function makeAvatar(User $user): string
    {
        if (null !== $user->avatar && '' !== $user->avatar) {
            return url($user->avatar);
        } elseif (null !== $user->social_avatar && '' !== $user->social_avatar) {
            return $user->social_avatar;
        } else {
            return self::makeGravatar($user->email);
        }
    }

    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email The email address
     * @param int $s     Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $d     Default imageset to use
     * @param string $r     Maximum rating (inclusive) [ g | pg | r | x ]
     * @param bool $img   True to return a complete IMG tag False for just the URL
     * @param array $atts  Optional, additional key/value attrs to include
     *
     * @return string String containing either just a URL or a complete image tag
     *
     * @see https://en.gravatar.com/site/implement/images/php/
     */
    public static function makeGravatar(
        string $email,
        int    $s = 80,
        string $d = 'mp',
        string $r = 'g',
        bool   $img = false,
        array $atts = []
    ): string {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val) {
                $url .= ' ' . $key . '="' . $val . '"';
            }
            $url .= ' />';
        }
        return $url;
    }

    /**
     * Return forum view
     *
     * @return View
     */
    public function forum(): View
    {
        $proposals = Proposal::all();
        $categories = Category::all();
        return view(
            'forum.home',
            ['proposals'=>$proposals, 'categories'=>$categories]
        );
    }

    /**
     * Return siginin view
     *
     * @return View
     */
    public function signIn(): View
    {
        return \View::make('auth.signin', []);
    }

    /**
     * Return view User registration
     *
     * @return View
     */
    public function userRegistration(): View
    {
        return \View::make('static.registration', []);
    }
}
