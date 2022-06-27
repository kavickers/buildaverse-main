<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Avatar;
use App\Models\Item;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AvatarsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        return response()->json([
            'success' => 'true',
            'message' => [
                'version' => config('app.framework'),
                'framework' => app()->version(),
            ],
        ], 200, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
    }

    public function v1()
    {
        return response()->json([
            'success' => 'false',
            'errors' => [
                'code' => '0',
                'message' => 'Something went wrong with that request, see response status code.'
            ],
        ], 404, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
    }

    /**
     * Buildaverse Avatar Rendering v1
     *
     * Systems created April 18th, 2021 at 4:30AM originally for BLOXCity.com
     *
     * Version: 1.0.0
     * Updated: 04/08/2021 4:30AM
     *
     */

    public function render(User $user)
    {
        if(Setting::where('avatar_enabled', '0')->get()->first())
        {
            return abort('403');
        }

        if($user->exists() && $user->avatar()->exists())
        {
            //make sure the user avatar wasn't rendered too quickly
            if(!$user->avatar_render || $user->avatar_render > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
            {
                return with('error', 'You\'re doing that too fast!');
                //return response()->json([
                //    'success' => 'false',
                //    'errors' => [
                //        'code' => '0',
                //        'message' => 'Something went wrong with that request, see response status code.'
                //    ],
                //], 429, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
            }

            //debug mode
            $debug = false;
            //retrieve the user's avatar
            $avatar = $user->avatar;
            
            //retrieve the sources for every object
            if(Item::find($avatar->shirt_id)) {
                $shirt_id = Item::find($avatar->shirt_id)->source;
            } else {
                $shirt_id = 0; 
            }
            if(Item::find($avatar->pants_id)) { 
                $pants_id = Item::find($avatar->pants_id)->source;
            } else {
                $pants_id = 0;
            }
            if(Item::find($avatar->face_id)) {
                $face_id = Item::find($avatar->face_id)->source;
            } else {
                $face_id = 0;
            }
            if(Item::find($avatar->hat1_id)) {
                $hat1_id = Item::find($avatar->hat1_id)->source;
            } else {
                $hat1_id = 0;
            }
            if(Item::find($avatar->hat2_id)) {
                $hat2_id = Item::find($avatar->hat2_id)->source;
            } else {
                $hat2_id = 0;
            }
            if(Item::find($avatar->hat3_id)) {
                $hat3_id = Item::find($avatar->hat3_id)->source;
            } else {
                $hat3_id = 0;
            }
            if(Item::find($avatar->tool_id)) {
                $tool_id = Item::find($avatar->tool_id)->source;
            } else {
                $tool_id = 0;
            }
            //begin setting variables to configure render command
            $shirt = "/var/www/storage/shirts/".$shirt_id.".png";
            $pants = "/var/www/storage/pants/".$pants_id.".png";
            $face = "/var/www/storage/faces/".$face_id.".png";
            $hat1 = "/var/www/storage/hats/".$hat1_id.".obj";
            $hat2 = "/var/www/storage/hats/".$hat2_id.".obj";
            $hat3 = "/var/www/storage/hats/".$hat3_id.".obj";
            $tool = "/var/www/storage/tools/".$tool_id.".obj";
            if($avatar->tool_id != 0) { $istool_int = 1; } else { $istool_int = 0; }
            $headcolor = $avatar->hex_head;
            $torsocolor = $avatar->hex_torso;
            $larmcolor = $avatar->hex_larm;
            $rarmcolor = $avatar->hex_rarm;
            $llegcolor = $avatar->hex_lleg;
            $rlegcolor = $avatar->hex_rleg;
            $randomHash = bin2hex(random_bytes(32));
            $output = "/var/www/cdn/".$randomHash.".png";

            //begin setting up the command
            $items = escapeshellarg($hat1)." ".escapeshellarg($hat2)." ".escapeshellarg($hat3)." ".escapeshellarg($tool);
            $colors =  escapeshellarg($rlegcolor)." ".escapeshellarg($llegcolor)." ".escapeshellarg($rarmcolor)." ".escapeshellarg($larmcolor)." ".escapeshellarg($headcolor)." ".escapeshellarg($torsocolor);
            $args = $colors." ".escapeshellarg($output)." ".escapeshellarg($shirt)." ".escapeshellarg($pants)." ".escapeshellarg($face)." ".escapeshellarg($istool_int)." ".$items;
            //build the final command
            $cmd = "/var/www/blender/blender -b -noaudio -P /var/www/storage/render.py -- default ".$args;

            if($debug)
            {
                echo system($cmd, $output) . "<br>".$cmd."<br>".$output;
                $user->avatar_url = $randomHash;
                $user->avatar_render = Carbon::now();
                $user->save();
            //    return response()->json([
            //        'success' => 'true',
            //        'hash' => $randomHash,
            //    ], 404, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
            } else {
                exec($cmd);
                $user->avatar_url = $randomHash;
                $user->avatar_render = Carbon::now();
                $user->save();
                return;
            //    return response()->json([
            //        'success' => 'true',
            //        'hash' => $randomHash,
            //    ], 404, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
            }
        }
        return;
        //return response()->json([
        //    'success' => 'false',
        //    'errors' => [
        //        'code' => '0',
        //        'message' => 'Something went wrong with that request, see response status code.'
        //    ],
        //], 404, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
    }

    public function market(Item $item)
    {
        if(Setting::where('avatar_enabled', '0')->get()->first())
        {
            return abort('403');
        }
        
        if($item->exists())
        {
            //debug mode
            $debug = false;
            //begin setting variables to configure render command
            if($item->type == 1)
            {
                $shirt = "/var/www/storage/shirts/0.png";
                $tshirt = "/var/www/storage/shirts/0.png";
                $pants = "/var/www/storage/pants/0.png";
                $face = "/var/www/storage/faces/0.png";
                $hat1 = "/var/www/storage/hats/".$item->source.".obj";
                $tool = "/var/www/storage/tools/0.obj";
            } elseif($item->type == 2) {
                $shirt = "/var/www/storage/shirts/0.png";
                $tshirt = "/var/www/storage/shirts/0.png";
                $pants = "/var/www/storage/pants/0.png";
                $face = "/var/www/storage/faces/".$item->source.".png";
                $hat1 = "/var/www/storage/hats/0.obj";
                $tool = "/var/www/storage/tools/0.obj";
            } elseif($item->type == 3) {
                $shirt = "/var/www/storage/shirts/0.png";
                $tshirt = "/var/www/storage/shirts/0.png";
                $pants = "/var/www/storage/pants/0.png";
                $face = "/var/www/storage/faces/0.png";
                $hat1 = "/var/www/storage/hats/0.obj";
                $tool = "/var/www/storage/tools/".$item->source.".obj";
            } elseif($item->type == 4) {
                $shirt = "/var/www/storage/shirts/".$item->source.".png";
                $tshirt = "/var/www/storage/shirts/0.png";
                $pants = "/var/www/storage/pants/0.png";
                $face = "/var/www/storage/faces/0.png";
                $hat1 = "/var/www/storage/hats/0.obj";
                $tool = "/var/www/storage/tools/0.obj";
            } elseif($item->type == 5) {
                $shirt = "/var/www/storage/shirts/0.png";
                $tshirt = "/var/www/storage/shirts/0.png";
                $pants = "/var/www/storage/pants/".$item->source.".png";
                $face = "/var/www/storage/faces/0.png";
                $hat1 = "/var/www/storage/hats/0.obj";
                $tool = "/var/www/storage/tools/0.obj";
            } elseif($item->type == 6) {
                $shirt = "/var/www/storage/shirts/0.png";
                $tshirt = "/var/www/storage/faces/".$item->source.".png";
                $pants = "/var/www/storage/pants/0.png";
                $face = "/var/www/storage/faces/0.png";
                $hat1 = "/var/www/storage/hats/0.obj";
                $tool = "/var/www/storage/tools/0.obj";
            }
            $hat2 = "/var/www/storage/hats/0.obj";
            $hat3 = "/var/www/storage/hats/0.obj";
            if($item->type == 3) { $istool_int = 1; } else { $istool_int = 0; }
            $headcolor = '999999';
            $torsocolor = '999999';
            $larmcolor = '999999';
            $rarmcolor = '999999';
            $llegcolor = '999999';
            $rlegcolor = '999999';
            $randomHash = bin2hex(random_bytes(32));
            $output = "/var/www/cdn/".$randomHash.".png";

            //begin setting up the command
            $items = escapeshellarg($hat1)." ".escapeshellarg($hat2)." ".escapeshellarg($hat3)." ".escapeshellarg($tool);
            $colors =  escapeshellarg($rlegcolor)." ".escapeshellarg($llegcolor)." ".escapeshellarg($rarmcolor)." ".escapeshellarg($larmcolor)." ".escapeshellarg($headcolor)." ".escapeshellarg($torsocolor);
            $args = $colors." ".escapeshellarg($output)." ".escapeshellarg($shirt)." ".escapeshellarg($pants)." ".escapeshellarg($face)." ".escapeshellarg($istool_int)." ".$items;
            //build the final command
            $cmd = "/var/www/blender/blender -b -noaudio -P /var/www/storage/render.py -- default ".$args;

            if($debug)
            {
                echo system($cmd) . "<br>".$cmd."<br>";
                $item->hash = $randomHash;
                $item->save();
                return;
            } else {
                exec($cmd);
                $item->hash = $randomHash;
                $item->save();
                return;
            }
        }
        return;
    }
}