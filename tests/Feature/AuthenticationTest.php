<?php
/**
 * Feature tests of API authentication
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
 * @category
 * @package
 * @author    Petros Diveris <petros@diveris.org>
 * @copyright 2019 Bentleyworks
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   GIT:
 * @link      https://github.com/pdiveris/sixproposals/blob/master/app/Http/Controllers/Auth/LoginController.php
 * @see       Six Acts
 */

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    // use DatabaseMigrations;
    use DatabaseTransactions;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $user = new User([
            'email'    => 'unverified@supermail.com',
            'password' => \Hash::make('123456'),
            'name'     => 'Pako Lucia'
        ]);
        $user->save();

        $user = new User([
            'email'    => 'verified@supermail.com',
            'password' => \Hash::make('123456'),
            'name'     => 'Al Di Meola',
            'verified' => 1,
        ]);
        $user->save();
    }

    public function testItWillRegisterAUser()
    {
        $response = $this->post('api/register', [
            'email'    => 'test2@email.com',
            'password' => '123456',
            'name'     => 'Mario Putzo',
        ]);
        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in'
        ]);
    }

    public function testDenyAccessToUnverifiedUser()
    {
        $response = $this->post('api/login', [
            'email'    => 'unverified@supermail.com',
            'password' => '123456'
        ]);

        $response->assertJsonStructure([
            'error',
        ]);
    }

    public function testAllowAccessToVerifiedUser()
    {
        $response = $this->post('api/login', [
            'email'    => 'verified@supermail.com',
            'password' => '123456'
        ]);

        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in'
        ]);
    }

    public function testItWillNotLogAnInvalidUserIn()
    {
        $response = $this->post('api/login', [
            'email'    => 'test@email.com',
            'password' => 'notlegitpassword'
        ]);
        $response->assertJsonStructure([
            'error',
        ]);
    }

}
