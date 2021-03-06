<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class   HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['home',]);
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function dashboard()
    {
        if ( auth()->user() && auth()->user()->can('view_adminpanel') ) {
            return view('dashboard.adminpanel.welcome');
        } else {
            return view('dashboard.userpanel.welcome');
        }
    }

    /**
     * Show the application dashboard.
     *
     */
    public function home()
    {
        return view('home');
    }

    public function contactUs(Request $request)
    {
        $message = 'Err#887s: Message not sent!';
        session()->flash('message', $message);
        return back();
    }
}
