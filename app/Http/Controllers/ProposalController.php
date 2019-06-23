<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProposalController extends Controller
{
    
    public function vote($direction = 1)
    {
    
    }
    
    public function voteUp()
    {
        $this->vote();
    }

    public function voteDown()
    {
        $this->vote(-1);
    }
}
