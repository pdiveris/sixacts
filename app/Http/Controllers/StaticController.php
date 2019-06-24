<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticController extends Controller
{
    public function content(Request $request)
    {
        $view = $request->path();
        return \View::make('static.'.$view, []);
    }
    
    public function gladdys()
    {
        return \View::make('static.gladdys', []);
    }
    
}
