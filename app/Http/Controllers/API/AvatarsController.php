<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Avatar;
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
        if($user->exists())
        {
            //debug mode
            $debug = true;
            //retrieve the user's avatar
            $avatar = Avatar::where('user_id', $user->id)->get()->first();
            //begin setting variables to configure render command
            $shirt = "/var/www/storage/assets/shirts/".$avatar->shirt_id.".png";
            $pants = "/var/www/storage/assets/pants/".$avatar->pants_id.".png";
            $face = "/var/www/storage/assets/faces/".$avatar->face_id.".png";
            $hat1 = "/var/www/storage/assets/hats/".$avatar->hat1_id.".obj";
            $hat2 = "/var/www/storage/assets/hats/".$avatar->hat2_id.".obj";
            $hat3 = "/var/www/storage/assets/hats/".$avatar->hat3_id.".obj";
            $tool = "/var/www/storage/assets/tools/".$avatar->tool_id.".png";
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
            $cmd = "blender -b -noaudio -P /var/www/storage/render.py -- default ".$args;

            if($debug)
            {
                echo system($cmd) . "<br>".$cmd."<br>";
                $user->avatar_url = $randomHash;
                $user->avatar_render = Carbon::now();
                $user->save();
                return response()->json([
                    'success' => 'true',
                    'hash' => $randomHash,
                ], 404, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
            } else {
                exec($cmd);
                $user->avatar_url = $randomHash;
                $user->avatar_render = Carbon::now();
                $user->save();
                return response()->json([
                    'success' => 'true',
                    'hash' => $randomHash,
                ], 404, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
            }
        }
        return response()->json([
            'success' => 'false',
            'errors' => [
                'code' => '0',
                'message' => 'Something went wrong with that request, see response status code.'
            ],
        ], 404, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
    }
}