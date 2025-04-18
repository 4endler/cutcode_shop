<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke()
    {
        // logger()->channel('telegram')->info('homepage');
        return view('homepage');
    }
}
