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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
}
