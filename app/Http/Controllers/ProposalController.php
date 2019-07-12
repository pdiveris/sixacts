<?php

namespace App\Http\Controllers;

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
     * @param int $direction direction (up/down)
     *
     * @return void
     */
    public function vote($direction = 1)
    {
        //
    }
    
    /**
     * Vote up handler
     *
     * @return void
     */
    public function voteUp()
    {
        $this->vote();
    }
    
    /**
     * Vote down handler
     *
     * @return void
     */
    public function voteDown()
    {
        $this->vote(-1);
    }
}
