<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticController extends Controller
{
    public function content()
    {
        return View::make('static.terms', []);
    }

}
