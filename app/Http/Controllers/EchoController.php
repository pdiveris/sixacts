<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EchoController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view(
            'static.echo.dash',
            []
        );
    }
}
