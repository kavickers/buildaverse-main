<?php

namespace App\Http\Controllers;

use App\Events\UserNotification;
use App\Models\Category;
use App\Models\ForumLike;
use App\Models\Reply;
use App\Models\Setting;
use App\Models\ThreadView;
use App\Models\Topic;
use App\Models\Thread;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ForumController extends Controller
{
    public function index(Request $request)
    {
        if(Setting::where('forum_enabled', '0')->get()->first())
        {
            return abort('403');
        }
        $sort = request('sort');
        $top = request('topic');

        $threads = Thread::orderBy('stuck', 'DESC')->orderBy('pinned', 'DESC')->orderBy('last_reply', 'DESC');

        if($sort)
        {
            switch ($sort) {
                case 'recent':
                    break;
                case 'trending':
                    $threads = $threads->where('views', '>', '49');
                    break;
                case 'official':
                    $threads = $threads->where('topic_id', '=', '1')->orWhere('topic_id', '=', '2');
                    break;
            }
        }

        if($top && $sort != 'official') {
            $threads = $threads->where('topic_id', '=', request('topic'));
        }

        $categories = Category::orderBy('id')->get();
        $topics = Topic::orderBy('id')->get();
        $threads = $threads->paginate(10);

        if($request->ajax())
        {
            $view = view('components.load_threads', compact('threads'))->render();
            return response()->json(['html' => $view]);
        }

        return view('forum.index', compact('topics', 'categories', 'threads'));
    }

    public function thread_like(Request $request, Thread $thread)
    {
        $getLike = ForumLike::where('target_id', '=', $thread->id)->where('user_id', '=', auth()->user()->id)->get()->first();

        if($thread->exists)
        {
            if($request->ajax())
            {
                $type = 1;
                if(!$getLike) {
                    $like = ForumLike::create([
                        'user_id' => auth()->id(),
                        'target_id' => $thread->id,
                        'target_type' => $type,
                    ]);
                    return response()->json(['success' => $like]);
                } elseif($getLike) {
                    $like = $getLike->delete();
                    return response()->json(['success' => $like]);
                }
            } else {
                return back();
            }
        } else {
            return back();
        }
    }

    public function reply_like(Request $request, Reply $reply)
    {
        $getLike = ForumLike::where('target_id', '=', $reply->id)->where('user_id', '=', auth()->user()->id)->get()->first();

        if($reply->exists)
        {
            if($request->ajax())
            {
                $type = 2;
                if(!$getLike) {
                    $like = ForumLike::create([
                        'user_id' => auth()->id(),
                        'target_id' => $reply->id,
                        'target_type' => $type,
                    ]);
                    return response()->json(['success' => $like]);
                } elseif($getLike) {
                    $like = $getLike->delete();
                    return response()->json(['success' => $like]);
                }
            } else {
                return back();
            }
        } else {
            return back();
        }
    }

    public function show_thread(Request $request, Thread $thread)
    {
        if(Setting::where('forum_enabled', '0')->get()->first())
        {
            return abort('403');
        }

        if(!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
        }

        if($thread->exists)
        {
            $checkView = ThreadView::where('thread_id', $thread->id)->where('ip', $_SERVER['REMOTE_ADDR'])->where('user_id', auth()->id());

            if(!$checkView->exists() && Auth::user()) {
                $thread->increment('views');
                ThreadView::insert(['thread_id' => $thread->id, 'ip' => $_SERVER['REMOTE_ADDR'], 'user_id' => auth()->id()]);
            }

            $topic = Topic::where('id', $thread->topic_id)->get()->first();
            $category = Category::where('id', $topic->category_id)->get()->first();

            $replies = Reply::where('thread_id', '=', $thread->id)->where('scrubbed', '=', 0)->orderBy('created_at', 'ASC')->paginate(5);

            if($request->ajax())
            {
                $view = view('components.load_replies', compact('replies'))->render();
                return response()->json(['html' => $view]);
            }

            return view('forum.thread', compact('thread', 'topic', 'category', 'replies'));
        } else {
            abort('403');
        }
    }

    public function create_thread(Request $request)
    {
        if(Setting::where('forum_enabled', '0')->get()->first())
        {
            return abort('403');
        }

        if(Setting::where('posts_enabled', '0')->get()->first())
        {
            return abort('403');
        }

        if(auth()->user()->power > 0)
        {
            $topics = Topic::all();
        } else {
            $topics = Topic::where('admin', '=', '0')->get();
        }

        return view('forum.new', compact(['topics']));
    }

    public function store_thread(Request $request)
    {
        if(Setting::where('forum_enabled', '0')->get()->first())
        {
            return abort(403);
        }

        if(Setting::where('posts_enabled', '0')->get()->first())
        {
            return abort(403);
        }

        if(!auth()->user()->flood_gate || auth()->user()->flood_gate > (Carbon::now()->subSeconds(env('FLOOD_GATE'))))
        {
            return back()->withInput()->with('error', 'Please wait '. env('FLOOD_GATE') . ' seconds before making another post.');
        }

        $this->validate($request, [
            'topic' => ['required'],
            'title' => ['required', 'max:50', 'min:3', 'alpha_dash'],
            'body' => ['required', 'max:3000', 'min:3', 'alpha_dash'],
        ]);

        $topic = Topic::where('id', '=', request('topic'))->get()->first();

        if($topic->exists)
        {
            if($topic->admin == 1)
            {
                if(auth()->user()->power > 0)
                {

                    $thread = Thread::create([
                        'user_id' => auth()->id(),
                        'topic_id' => $topic->id,
                        'title' => request('title'),
                        'body' => request('body'),
                        'last_reply' => Carbon::now(),
                    ]);

                    $flood = Auth::user();
                    $flood->flood_gate = Carbon::now();
                    $flood->save();

                    return redirect($thread->path());
                } else {
                    return abort('403');
                }
            } else {

                $thread = Thread::create([
                    'user_id' => auth()->id(),
                    'topic_id' => $topic->id,
                    'title' => request('title'),
                    'body' => request('body'),
                    'last_reply' => Carbon::now(),
                ]);

                $flood = Auth::user();
                $flood->flood_gate = Carbon::now();
                $flood->save();

                return redirect($thread->path());
            }
        } else {
            return abort('404');
        }
    }

    public function lock_thread(Thread $thread)
    {
        if(Auth::user()->power > 0)
        {
            if($thread->locked)
            {
                $thread->unlock();
            } else {
                $thread->lock();
            }

            return back();
        } else {
            return abort('403');
        }
    }

    public function pin_thread(Thread $thread)
    {
        if(Auth::user()->power > 0)
        {
            if($thread->pinned)
            {
                $thread->unpin();
            } else {
                $thread->pin();
            }

            return back();
        } else {
            return abort('403');
        }
    }

    public function show_quote(Thread $thread, $quote_id, $quote_type)
    {
        if(Setting::where('forum_enabled', '0')->get()->first())
        {
            return abort('403');
        }

        if(Setting::where('posts_enabled', '0')->get()->first())
        {
            return abort('403');
        }

        if($thread->exists)
        {
            $quote = null;
            if($thread->locked)
            {
                return abort('403');
            }

            if($quote_type == 1)
            {
                $quote = $thread;

                if($quote_id != $thread->id)
                {
                    return abort('403');
                }
            }

            if($quote_type == 2)
            {
                $quote = Reply::where('id', $quote_id)->get()->first();

                if($quote->thread_id != $thread->id)
                {
                    return abort('403');
                }
            }

            if(!$quote->exists)
            {
                return abort('404');
            }

            $topic = Topic::where('id', $thread->topic_id)->get()->first();

            return view('forum.quote', compact(['thread', 'topic', 'quote', 'quote_type']));
        } else {
            return abort('404');
        }
    }

    public function store_quote(Request $request, Thread $thread, $quote_id, $quote_type)
    {
        if(Setting::where('forum_enabled', '0')->get()->first())
        {
            return abort('403');
        }

        if(Setting::where('posts_enabled', '0')->get()->first())
        {
            return abort('403');
        }

        if($thread->locked)
        {
            return abort('403');
        }

        if($quote_type == 1)
        {
            $quote = $thread;

            if($quote_id != $thread->id)
            {
                return abort('403');
            }
        }

        if($quote_type == 2)
        {
            $quote = Reply::where('id', $quote_id)->get()->first();

            if($quote->thread_id != $thread->id)
            {
                return abort('403');
            }
        }

        if(!$quote->exists)
        {
            return abort('404');
        }

        if(!auth()->user()->flood_gate || auth()->user()->flood_gate > (Carbon::now()->subSeconds(env('FLOOD_GATE'))))
        {
            return back()->withInput()->with('error', 'Please wait '. env('FLOOD_GATE') . ' seconds before making another post.');
        }

        $this->validate($request, [
            'body' => ['required', 'max:3000', 'min:3', 'alpha_dash']
        ]);

        if($thread->exists)
        {
            $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id(),
                'topic_id' => $thread->topic_id,
                'quote_id' => $quote->id,
                'quote_type' => $quote_type,
            ]);

            $flood = Auth::user();
            $flood->flood_gate = Carbon::now();
            $flood->save();

            $update = $thread;
            $update->last_reply = Carbon::now();
            $update->save();

            if($quote->owner->username != auth()->user()->username)
            {
                $message = auth()->user()->username . " quoted you in a post.";
                UserNotification::dispatch($message, 'forum-quote', $thread->path(), $quote->owner);
            }

            return redirect($thread->path());
        } else {
            return back();
        }
    }

    public function create_reply(Thread $thread)
    {
        if(Setting::where('forum_enabled', '0')->get()->first())
        {
            return abort('403');
        }

        if(Setting::where('posts_enabled', '0')->get()->first())
        {
            return abort('403');
        }

        if($thread->exists)
        {
            if($thread->locked)
            {
                return abort('403');
            }

            $topic = Topic::where('id', $thread->topic_id)->get()->first();

            return view('forum.reply', compact(['thread', 'topic']));
        } else {
            return abort('404');
        }
    }

    public function store_reply(Request $request, Thread $thread)
    {
        if(Setting::where('forum_enabled', '0')->get()->first())
        {
            return abort('403');
        }

        if(Setting::where('posts_enabled', '0')->get()->first())
        {
            return abort('403');
        }

        if($thread->locked)
        {
            return abort('403');
        }

        if(!auth()->user()->flood_gate || auth()->user()->flood_gate > (Carbon::now()->subSeconds(env('FLOOD_GATE'))))
        {
            return back()->withInput()->with('error', 'Please wait '. env('FLOOD_GATE') . ' seconds before making another post.');
        }

        $this->validate($request, [
            'body' => ['required', 'max:3000', 'min:3', 'alpha_dash']
        ]);

        if($thread->exists)
        {
            $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id(),
                'topic_id' => $thread->topic_id,
            ]);

            $flood = Auth::user();
            $flood->flood_gate = Carbon::now();
            $flood->save();

            $update = $thread;
            $update->last_reply = Carbon::now();
            $update->save();

            if($thread->owner->username != auth()->user()->username)
            {
                $message = auth()->user()->username . " replied to your thread.";
                UserNotification::dispatch($message, 'forum-reply', $thread->path(), $thread->owner);
            }

            return redirect($thread->path());
        } else {
            return back();
        }
    }
}
