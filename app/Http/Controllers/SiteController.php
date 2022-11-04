<?php
/**
 * Home Controller
 *
 * Handles aspects of the login process,
 * including syncing with OAuth data from external providers
 *
 * PHP version 7.2
 *
 * LICENSE: This source file is subject to version 2.0 of the Apache License
 * that is available through the world-wide-web at the following URI:
 * https://www.apache.org/licenses/LICENSE-2.0.
 *
 * @category  Controller
 * @package   App
 * @author    Petros Diveris <petros@diveris.org>
 * @copyright 2019 Bentleyworks
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   GIT: $Id$
 * @link      https://github.com/pdiveris/sixproposals/blob/master/app/Http/Controllers/Auth/LoginController.php
 * @see       Six Acts
 */
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Proposal;
use App\Models\User;
use Auth;
use Cache;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request as Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Psr\SimpleCache\InvalidArgumentException;
use Validator;

/**
 * Class SiteController
 *
 * @category Controller
 * @package  App\Http\Controllers
 * @author   Petros Diveris <petros@diveris.org>
 * @license  Apache 2.0 http://www.php.net/license/3_01.txt
 * @release  0.1
 * @link     https://github.com/pdiveris/sixproposals/blob/master/app/Http/Controllers/Auth/LoginController.php
 */
class SiteController extends Controller
{
    /**
     * @var \Request request
     */
    private \Request $request;

    /**
     * Create a new controller instance.
     *
     * @param \Request $request
     *
     */
    public function __construct(\Request $request)
    {
        $this->request = $request;

        $earl = $request->getUri();
        $host = $request->getHost();

        $path = str_replace('https://' . $host . '/', '', $earl);

        if (str_contains($path, 'become')) {
            //
        } else {
            $this->middleware('auth');
        }

    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index(): Response
    {
        return view('home');
    }

    /**
     * Show the User's profile page/form
     *
     * @return Factory|View
     */
    public function userProfile(): Factory|View
    {
        $oAuthUser = Auth::user();
        if (!$oAuthUser) {
            abort(419, 'Page has expired');
        }
        $user = User::find((int)$oAuthUser->id);

        return view('user_profile',
            [
                'user' => $user,
                'sixjs' => '1',
                'croppie' => '1',
            ]
        );
    }

    /**
     * Process the updated user profile
     *
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function postUserProfile(Request $request): Redirector|RedirectResponse
    {
        $rules = array(
            'name'    => 'required|string',
            'email' => 'email:rfc,dns',
        );

        if ($request->get('password') !== '' &&
            $request->get('password') !== null &&
            $request->get('password_confirmation') !== '' &&
            $request->get('password_confirmation') !== null
        ) {
            $rules['password'] = 'string|confirmed';
            $rules['password_confirmation'] = 'string';
        }

        $validator = Validator::make($this->request->all(), $rules);

        if ($validator->fails()) {
            return redirect('/user/profile')
                ->withErrors($validator)
                ->withInput($this->request->all());
        } else {
            $oauthUser = Auth::user();
            if (null === $oauthUser) {
                abort(501, 'System error retrieving your details. We are working on it..');
            }

            $user = User::find($oauthUser->id);
            $user->email = $request->get('email');
            $user->name = $request->get('name');
            $user->display_name = $request->get('display_name');

            if ($request->get('password') !== null && $request->get('password') !== '') {
                $user->password = bcrypt($request->get('password'));
            }

            $user->save();
            return redirect('/user/profile')
                ->with('success', 'Your details have been updated')
                ->withInput($this->request->all());
        }
    }

    /**
     * Display a form to collect the proposed action
     *
     * @return Factory|View
     */
    public function getProposal(): Factory|View
    {
        $pause = Cache::get('pause', 'off');

        $categories = Category::all();
        foreach ($categories as $category) {
            $category->selected = false;
        }

        $this->request->session()->flash('warning', 'Record successfully added!');

        return view(
            'proposal_form',
            [
                'categories' => $categories,
                'sixjs' => '1',
                'pause' => $pause
            ]
        );
    }

    /**
     * Handle the form/
     * Validate to save, that's our mission.
     *
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function postProposal(Request $request): Redirector|RedirectResponse
    {
        // validate the info, create rules for the inputs
        $rules = [
            'title' => 'required|string',
            'body' => 'required|min:3',
            'category_id' => 'required|integer|between:1,65535',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('propose')
                ->withErrors($validator)
                ->withInput($this->request->all());
        }

        $proposal = new Proposal();
        $proposal->user_id = auth()->user()->id;
        $proposal->title = $request->get('title');
        $proposal->category_id = $request->get('category_id');
        $proposal->body = $request->get('body');

        if ($proposal->save()) {
            return redirect('propose')
                ->with(['type' => 'success', 'message' => 'Your proposal has been added to the Six Acts']);
        }
        abort(520);
    }

    /**
     * Method to overcome the ludicrous splash screen when working internally
     * Do not show the splash if
     * - running locally
     *
     * @return bool
     */
    public static function showModal(): bool
    {
        $request = Request();

        if ($request->get('splash', '') === 'please') {
            return true;
        }

        if ($request->getHost() ==='sixacts.div'
            || $request->ip() === '127.0.0.1'
            || $request->ip() === '10.17.1.254'
            || $request->get('nosplash', '') === 'please'
        ) {
            return false;
        }

        if (\Session::get('campaigner', false) === true) {
            return false;
        } else {
            return env('SPLASH_SCREEN', false);
        }
    }

    /**
     * Scheduled task
     *
     * @return mixed
     */
    public static function getTwitts(): mixed
    {
        $twits =  json_decode(Cache::get('twitter', '[]'));
        foreach ($twits as $twit) {
            foreach ($twit->entities->urls as $i=>$url) {
                $twit->real_url = $url->url;
            }
        }
        return $twits;
    }

    /**
     * If voting is ON, turn it off
     * If voting is OFF turn it on
     *
     * @return RedirectResponse|Redirector
     * @throws InvalidArgumentException
     */
    public function pause(): Redirector|RedirectResponse
    {
        $user = Auth::user();
        if ($user && in_array($user->id, [1 ,2 ,3])) {
            $status = Cache::get('pause', 'off');
            echo 'Found the cache $status<br />';

            if ($status === 'off') {
                echo 'Setting pause to on';
                Cache::set('pause', 'on', 1800);
            }
            else {
                echo 'Setting pause to off, forever';
                Cache::set('pause', 'off');
            }
            echo '<br/>';
        }
        return redirect('/');
    }

    /**
     * Become someone else
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function become(Request $request, $id): Redirector|RedirectResponse
    {
        if (!in_array($request->ip(), ['10.17.1.254', '88.97.78.236', '127.0.0.1'])) {
            abort(404, 'Not found');
        }
        Auth::loginUsingId($id);

        return redirect('/');
    }
}
