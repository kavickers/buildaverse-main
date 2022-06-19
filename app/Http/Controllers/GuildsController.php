<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuildsController extends Controller
{
    public function index()
    {
        if(auth()->user()->guildsCount() < 1)
        {
            return redirect(route('guilds.search')); //change this to guilds.explore when that section is ready for release
        }
        return view('guilds.index');
    }
    public function view()
    {
        return view('guilds.view');
    }
    public function search()
    {
        return view('guilds.search');
    }
    public function explore()
    {
        return redirect(route('guilds.search'));
        //readd the below when the explore page is ready
        //return view('guilds.explore');
    }
    public function edit()
    {
        return view('guilds.edit');
    }
    public function create()
    {
        return view('guilds.create');
    }
}
