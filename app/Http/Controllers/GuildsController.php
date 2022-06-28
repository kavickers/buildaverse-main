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

    public function view(Request $request, Guild $guild)
    {
        if($guild && $guild->is_locked == 0)
        {
            return view('guilds.view', compact(['guild']));
        } else {
            return abort(404);
        }
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
            'name' => 'required|min:3|max:40|regex:/^[a-z0-9 .\-!,\':;<>?()\[\]+=\/#$&]+$/i|unique:guilds',
            'desc' => 'max:2048|regex:/^[a-z0-9 .\-!,\':;<>?()\[\]+=\/#$&\t\n\r]+/i|nullable',
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2048',
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
            'thumbnail_url' => $imageName,
        ]);

        GuildRank::insert([
            [
                'guild_id' => $guild->id,
                'name' => 'Owner',
                'rank' => 255,
                'can_view_wall' => true,
                'can_post_on_wall' => true,
                'can_moderate_wall' => true,
                'can_view_audit' => true,
                'can_advertise' => true,
                'can_change_ranks' => true,
                'can_kick_members' => true,
                'can_accept_members' => true,
                'can_post_announcements' => true,
                'can_spend_funds' => true,
                'can_create_items' => true,
                'can_edit_games' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'guild_id' => $guild->id,
                'name' => 'Admin',
                'rank' => 254,
                'can_view_wall' => true,
                'can_post_on_wall' => true,
                'can_moderate_wall' => true,
                'can_view_audit' => true,
                'can_advertise' => true,
                'can_change_ranks' => true,
                'can_kick_members' => false,
                'can_accept_members' => true,
                'can_post_announcements' => true,
                'can_spend_funds' => false,
                'can_create_items' => true,
                'can_edit_games' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'guild_id' => $guild->id,
                'name' => 'Member',
                'rank' => 1,
                'can_view_wall' => true,
                'can_post_on_wall' => true,
                'can_moderate_wall' => false,
                'can_view_audit' => false,
                'can_advertise' => false,
                'can_change_ranks' => false,
                'can_kick_members' => false,
                'can_accept_members' => false,
                'can_post_announcements' => false,
                'can_spend_funds' => false,
                'can_create_items' => false,
                'can_edit_games' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]); // create default ranks
        GuildMember::create([
            'guild_id' => $guild->id,
            'user_id' => auth()->user()->id,
            'rank' => 255,
        ]); // add the owner as the default member of the group

        $flood = auth()->user();
        $flood->flood_gate = Carbon::now();
        $flood->save();

        return redirect(route('guilds.view', $guild->id)); // created group success
        
    }
}
