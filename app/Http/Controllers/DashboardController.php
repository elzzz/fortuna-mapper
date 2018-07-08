<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Mapper;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        Mapper::map(55.751244, 37.618423, ['marker' => false]);
        foreach($user->markers as $marker) {
            Mapper::informationWindow($marker->lat, $marker->long, $marker->description . "<br><small>Added by ".$marker->user->name."</small>");
        }
        return view('dashboard')->with('markers', $user->markers);
    }
}
