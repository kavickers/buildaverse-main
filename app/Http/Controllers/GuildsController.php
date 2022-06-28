<?php

namespace App\Http\Controllers;

use App\Models\Guild;
use App\Models\GuildMember;
use App\Models\GuildRank;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function create_post(Request $request)
    {

        if(!auth()->user()->flood_gate || auth()->user()->flood_gate > (Carbon::now()->subSeconds(env('FLOOD_GATE'))))
        {
             return back()->with('error', 'You\'re doing that too fast!');
        }

        $request->validate([
            'name' => 'required|min:3|max:64|regex:/^[a-z0-9 .\-!,\':;<>?()\[\]+=\/#$&]+$/i',
            'desc' => 'max:2048|regex:/^[a-z0-9 .\-!,\':;<>?()\[\]+=\/#$&\t\n\r]+/i',
            'image' => 'required|image|mimes:png|max:2048',
        ]);

        $realName = bin2hex(random_bytes(32));
        $imageName = $realName.'.'.request()->image->extension();

        $disk = Storage::build([
            'driver' => 'local',
            'root' => '/var/www/cdn',
        ]);

        $disk->putFileAs('', $request->image, $imageName);

        $guild = Guild::create([
            'owner_id' => auth()->user()->id,
            'name' => request('name'),
            'desc' => request('desc'),
            'thumbnail_url' => $realName,
        ]);

        GuildRank::create([]); // create default ranks
        GuildMember::create([]); // add the owner as the default member of the group

        return 'created'; // created group success
    }
}
