<?php

namespace App\Http\Controllers;

use App\Models\Blurb;
use App\Models\Comment;
use App\Models\Item;
use App\Models\Thread;
use App\Models\Reply;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{

    /**
     *
     * Report types
     * 1 = Thread
     * 2 = Reply
     * 3 = User
     * 4 = Blurb
     * 5 = Item
     * 6 = Comment
     *
     */

    public function report_thread(Thread $thread)
    {
        if(!$thread->exists) {
            return abort(403);
        }

        $rid = $thread->id;
        $type = 1;
        $route = "report.threads.submit";
        $uid = $thread->user_id;

        $user = User::firstOrFail('id', $uid)->first();
        $username = $user->username;

        return view('misc.report', compact(['rid', 'type', 'route', 'uid', 'username']));
    }

    public function report_reply(Reply $reply)
    {
        if(!$reply->exists) {
            return abort(403);
        }

        $rid = $reply->id;
        $type = 2;
        $route = "report.reply.submit";
        $uid = $reply->user_id;

        $user = User::firstOrFail('id', $uid)->first();
        $username = $user->username;

        return view('misc.report', compact(['rid', 'type', 'route', 'uid', 'username']));
    }

    public function report_user(User $user)
    {
        if(!$user->exists) {
            return abort(403);
        }

        $rid = $user->id;
        $type = 3;
        $route = "report.user.submit";
        $uid = $user->id;

        $user = User::firstOrFail('id', $uid)->first();
        $username = $user->username;

        return view('misc.report', compact(['rid', 'type', 'route', 'uid', 'username']));
    }

    public function report_blurb(Blurb $blurb)
    {
        if(!$blurb->exists)
        {
            return abort(403);
        }
        $rid = $blurb->id;
        $type = 4;
        $route = "report.blurb.submit";
        $uid = $blurb->owner->id;

        $user = $blurb;
        $username = $user->owner->username;

        return view('misc.report', compact(['rid', 'type', 'route', 'uid', 'username']));
    }

    public function report_item(Item $item)
    {
        if(!$item->exists)
        {
            return abort(403);
        }
        $rid = $item->id;
        $type = 5;
        $route = "report.blurb.submit";
        $uid = $item->owner->id;

        $user = $item;
        $username = $user->owner->username;

        return view('misc.report', compact(['rid', 'type', 'route', 'uid', 'username']));
    }

    public function report_comment(Comment $comment)
    {
        if(!$comment->exists)
        {
            return abort(403);
        }
        $rid = $comment->id;
        $type = 6;
        $route = "report.blurb.submit";
        $uid = $comment->owner->id;

        $user = $comment;
        $username = $user->owner->username;

        return view('misc.report', compact(['rid', 'type', 'route', 'uid', 'username']));
    }

    public function submit(Request $request)
    {
        $rules = array(
            "Spam",
            "Excessive Profanity",
            "Sexual Content",
            "Sensitive Topics",
            "Offsite Links",
            "Harassment / Discrimination",
            "Exploiting / Cheating",
            "Account Theft - Phishing / Hacking",
            "Other",
        );
        $this->validate($request, [
            'rule' => ['required'],
        ]);

        if(!in_array($request['rule'], $rules))
        {
            return abort(403);
        }

        Report::create([
            'by' => Auth::user()->id,
            'uid' => $request['uid'],
            'rid' => $request['rid'],
            'type' => $request['type'],
            'rule' => $request['rule'],
        ]);

        return redirect('/');
    }
}
