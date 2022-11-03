<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EchoController extends Controller
{
    /**
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request): Factory|View
    {
        return view(
            'static.echo.dash',
            []
        );
    }
}
