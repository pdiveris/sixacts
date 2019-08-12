<?php
    /**
     * Site helpers to be access via facade in views
     *
     * Deals with site specific things such enabled/disabled menu items
     * Prelaunch/post launch functionalities etc
     *
     * PHP version 7.2
     *
     * LICENSE: This source file is subject to version 2.0 of the Apache License
     * that is available through the world-wide-web at the following URI:
     * https://www.apache.org/licenses/LICENSE-2.0.
     *
     * @category
     * @package
     * @author    Petros Diveris <petros@diveris.org>
     * @copyright 2019 Bentleyworks
     * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
     * @version   GIT:
     * @link      https://github.com/pdiveris/sixproposals/blob/master/app/Http/Controllers/Auth/LoginController.php
     * @see       Six Acts
     */
    
namespace App\Helpers;

class Site
{
    protected static $menuItems = [
            'about'=> 1,
            'categories' => 1,
            'nine_rules' => 1,
            'terms' => 1,
            'privacy' => 1,
            'register' => 0,
            'signin' => 0,
            'login' => 0,
            'propose' => 0,
        ];
    /**
     * Dummy method from the early testing days
     *
     * @return string
     */
    public function getGreeting(): string
    {
        return "The mother of helpers says hello hello";
    }
    
    public function menuLink(string $endPoint): string
    {
        if (env('LAUNCH') || \Session::get('campaigner', false)===true) {
            return $endPoint;
        }
        if (array_key_exists($endPoint, self::$menuItems)) {
            return self::$menuItems[$endPoint] === 1 ? $endPoint : '#';
        }
    }
}
