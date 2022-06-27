<?php

namespace App\Http\Controllers;

use App\Events\UserNotification;
use App\Helpers\GhostBlog;
use App\Models\Blurb;
use App\Models\Friend;
use App\Models\Privacy;
use App\Models\ProfileView;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Exception;

class UserController extends Controller
{
    public function index()
    {
        if(auth()->user()) {
            return redirect(route('dashboard'));
        } else {
            return view('welcome');
        }
    }

    public function dashboard(Request $request)
    {
        $blurbs = auth()->user()->get_feed();

        $posts = GhostBlog::latest(4);

        if($request->ajax() && !$blurbs->isEmpty())
        {
            $view = view('components.load_user_feed', compact('blurbs'))->render();
            return response()->json(['html' => $view]);
        }

        return view('dashboard', compact(['blurbs', 'posts']));
    }

    public function post_blurb(Request $request)
    {
        if(!auth()->user()->flood_gate || auth()->user()->flood_gate > (Carbon::now()->subSeconds(env('FLOOD_GATE'))))
        {
            return back()->withInput()->with('error', 'Please wait '. env('FLOOD_GATE') . ' seconds before making another post.');
        }

        $request->validate([
            'text' => ['required', 'max:140', 'min:3', 'regex:/^[a-z0-9 .\-!,\':;<>?()\[\]+=\/#$&]+$/i'],
        ]);

        Blurb::create([
            'author_id' => auth()->id(),
            'author_type' => 1,
            'text' => request('text'),
        ]);

        $flood = auth()->user();
        $flood->flood_gate = Carbon::now();
        $flood->save();

        return back()->with('success', 'Successfully updated status!');
    }

    public function maintenance()
    {
        return view('maintenance.index');
    }

    public function achievements()
    {
        $data = config('badges');
        $badges = [];

        foreach($data as $i => $badge)
        {
            $badge = new \stdClass;
            $badge->id = $i;
            $badge->name = $data[$i]['name'];
            $badge->description = $data[$i]['description'];
            $badge->image = asset("img/badges/{$data[$i]['image']}.png");
            $badge->category = $data[$i]['category'];
            $badge->enabled = $data[$i]['enabled'];

            $badges[] = $badge;
        }
        return view('misc.achievements', compact(['badges']));
    }

    public function profile($id)
    {
        $user = User::find($id);

        if(!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
        }

        if($user && $user->deleted == 0)
        {
            if(auth()->user())
            {
                $checkView = ProfileView::where('target_id', $user->id)->where('ip', $_SERVER['REMOTE_ADDR'])->where('user_id', auth()->id());


                if(!$checkView->exists()) {
                    $user->increment('views');
                    ProfileView::insert(['target_id' => $user->id, 'ip' => $_SERVER['REMOTE_ADDR'], 'user_id' => auth()->id()]);
                }
            }
            return view('user.profile', compact(['user']));
        } else {
            return abort(404);
        }
    }

    public function friends(Request $request, User $user)
    {
        if($user && $user->deleted == 0)
        {
            if(auth()->user()->id == $user->id)
            {
                return redirect(route('user.myfriends'));
            }
            $friends = $user->getFriends(9);

            return view('user.friends', compact(['user', 'friends']));
        } else {
            return abort(404);
        }
    }

    public function my_friends(Request $request)
    {
        $requests = Auth::user()->getFriendRequests();
        $friends = Auth::user()->getFriends(9, 'friends');

        return view('user.myfriends', compact(['requests', 'friends']));
    }

    public function add_friend(User $user)
    {
        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        if(auth()->user()->befriend($user)) {
            $message = auth()->user()->username . " sent you a friend request.";
            UserNotification::dispatch($message, 'friend-request', '/user/'.auth()->user()->id, $user);

            $flood = auth()->user();
            $flood->action_flood_gate = Carbon::now();
            $flood->save();

            return back()->with('success', 'Sent friend request to ' . $user->username . '.');
        } else {
            return back()->with('error', 'Friend request already exists!');
        }
    }

    public function remove_friend(User $user)
    {
        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        if(auth()->user()->unfriend($user)) {
            $flood = auth()->user();
            $flood->action_flood_gate = Carbon::now();
            $flood->save();

            return back()->with('success', 'Removed ' . $user->username . ' from your friends.');
        } else {
            return back()->with('error', 'You\'re not friends with that user!');
        }
    }

    public function accept_friend(User $user)
    {
        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        if(auth()->user()->acceptFriendRequest($user))
        {
            $message = auth()->user()->username . " accepted your friend request.";
            UserNotification::dispatch($message, 'friend-accepted', '/user/'.auth()->user()->id, $user);

            $flood = auth()->user();
            $flood->action_flood_gate = Carbon::now();
            $flood->save();

            return back()->with('success', $user->username . ' is now your friend!');
        } else {
            return back()->with('error', 'Friend request does not exist!');
        }
    }
    public function decline_friend(User $user)
    {
        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        if(auth()->user()->denyFriendRequest($user)) {
            $flood = auth()->user();
            $flood->action_flood_gate = Carbon::now();
            $flood->save();

            return back()->with('success', 'Declined friend request from ' . $user->username . '.');
        } else {
            return back()->with('error', 'Friend request does not exist!');
        }
    }
    public function accept_all_friends()
    {
        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        foreach(auth()->user()->getFriendRequests() as $request)
        {
            if(auth()->user()->acceptFriendRequest($request->sender)) {
                $message = auth()->user()->username . " accepted your friend request.";
                UserNotification::dispatch($message, 'friend-accepted', '/user/'.auth()->user()->id, $request->sender);
            }
        }

        $flood = auth()->user();
        $flood->action_flood_gate = Carbon::now();
        $flood->save();

        return back()->with('success', 'Accepted all friend requests!');
    }
    public function decline_all_friends()
    {
        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        foreach(auth()->user()->getFriendRequests() as $request)
        {
            auth()->user()->denyFriendRequest($request->sender);
        }

        $flood = auth()->user();
        $flood->action_flood_gate = Carbon::now();
        $flood->save();

        return back()->with('success', 'Declined all friend requests.');
    }

    public function settings()
    {
        return view('user.settings');
    }

    public function money(Request $request)
    {
        $transactions = auth()->user()->transactions()->paginate(5);

        if($request->ajax() && $transactions->count() > 0)
        {
            $view = view('components.load_user_transactions', compact('transactions'))->render();
            return response()->json(['html' => $view]);
        }

        return view('user.money', compact(['transactions']));
    }

    public function transfer_cash(Request $request)
    {
        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        $this->validate($request, [
            'cash' => ['required', 'numeric'],
        ]);

        if(request('cash') > auth()->user()->cash) {
            return back()->with('error', 'You need more currency to complete this transaction!');
        }

        $num = request('cash') * 10;

        if($num % 10 == 0)
        {
            $user = auth()->user();
            $user->cash = $user->cash - request('cash');
            $user->coins = $user->coins + $num;
            $user->action_flood_gate = Carbon::now();
            $user->save();

            return back()->with('success', 'You have succesfully transferred your currency!');
        } else {
            return back()->with('error', 'Number must be divisble by 10.');
        }
    }

    public function transfer_coins(Request $request)
    {
        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        $this->validate($request, [
            'coins' => ['required', 'numeric'],
        ]);

        if(request('coins') > auth()->user()->coins) {
            return back()->with('error', 'You need more currency to complete this transaction!');
        }

        if(request('coins') < 10) {
            return back()->with('error', 'You must transfer a minimum of 10.');
        }

        if(request('coins') % 10 == 0)
        {
            $num = request('coins') / 10;
            $user = auth()->user();
            $user->coins = $user->coins - request('coins');
            $user->cash = $user->cash + $num;
            $user->action_flood_gate = Carbon::now();
            $user->save();

            return back()->with('success', 'You have succesfully transferred your currency!');
        } else {
            return back()->with('error', 'Number must be divisble by 10.');
        }
    }

    public function update_settings_privacy(Request $request)
    {
        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        $this->validate($request, [
            'message' => 'required',
            'inventory' => 'required',
            'blurb' => 'required',
            'trade' => 'required',
        ]);

        $privacy = Privacy::where('user_id', auth()->user()->id)->first();
        $privacy->message = $request['message'];
        $privacy->inventory = $request['inventory'];
        $privacy->blurb = $request['blurb'];
        $privacy->trade = $request['trade'];

        $privacy->save();

        $flood = auth()->user();
        $flood->action_flood_gate = Carbon::now();
        $flood->save();

        return back()->with('success', 'Your privacy settings have been updated!');
    }

    public function update_settings_general(Request $request)
    {
        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        $this->validate($request, [
            'description' => ['nullable', 'max:280', 'regex:/^[a-z0-9 .\-!,\':;<>?()\[\]+=\/#$&\t\n\r]+/i'],
            'signature' => ['nullable', 'max:50', 'regex:/^[a-z0-9 .\-!,\':;<>?()\[\]+=\/#$&]+$/i'],
            'birthday' => ['required', 'date'],
            'theme' => ['required'],
        ]);

        $user = User::where('id', Auth::user()->id)->first();

        // Update user data
        if($user->biography != $request['description'])
        {
            $user->biography = $request['description'];
        }

        if($user->signature != $request['signature'])
        {
            $user->signature = $request['signature'];
        }

        if($user->birthday != $request['birthday'])
        {
            $user->birthday = $request['birthday'];
        }

        if($user->theme != $request['theme'])
        {
            $user->theme = $request['theme'];
        }

        $user->save();

        $flood = auth()->user();
        $flood->action_flood_gate = Carbon::now();
        $flood->save();

        return redirect()->route('user.settings')->with('success', 'Your settings have been updated!');
    }

    public function logout_other_sessions(Request $request)
    {
        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        $this->validate($request, [
            'password' => 'required',
        ]);

       try {
           Auth::logoutOtherDevices($request->get('password'));
           return back()->with('success', 'Logged out of all other sessions.');
       } catch (\Exception) {
           return back()->with('error', 'Incorrect password.');
       }
    }
}
