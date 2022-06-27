<?php

namespace App\Models;

use App\Traits\Friendable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Blurb;

class User extends Authenticatable
{

    use HasFactory, Notifiable, Friendable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'birthday',
        'avatar_url',
        'cash',
        'coins',
        'last_currency',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that are dates.
     *
     * @var array
     */
    protected $dates = [
        'last_online', 'flood_gate', 'action_flood_gate'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

     /** Functions related to the user are below */

     //Will determine if the user is online
    public function isOnline()
    {
         if($this->last_online->gt(Carbon::now()->subMinutes(2)))
         {
             return true;
         } else {
             return false;
         }
    }

    protected function getOrPaginate($builder, $perPage)
    {
        if ($perPage == 0) {
            return $builder->get();
        }
        return $builder->paginate($perPage);
    }

    public function get_feed()
    {
        $blurbs = Blurb::whereIn('author_id', auth()->user()->getFriends()->pluck('id'))->orWhere('author_id', auth()->user()->id)->where('scrubbed', '=', '0')->latest()->paginate(15);
        return $blurbs;
    }

    public function items()
    {
        $this->hasMany(Item::class, 'creator_id');
    }

    public function comments()
    {
        $this->hasMany(Comment::class)->where('scrubbed', '=', '0');
    }

    public function privacy()
    {
        if($this->hasOne(Privacy::class)->exists())
        {
            return $this->hasOne(Privacy::class);
        } else {
            Privacy::create(['user_id' => $this->id]);
            return $this->hasOne(Privacy::class);
        }
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id')->orderBy('created_at', 'DESC');
    }

    public function released_transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id')->where('release_at', '<', Carbon::now()->subDay())->where('released', '=', 0);
    }

    public function threads()
    {
        return $this->hasMany(Thread::class)->where('scrubbed', '=', '0');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class)->where('scrubbed', '=', '0');
    }

    public function posts()
    {
        return $this->replies()->count() + $this->threads()->count();
    }

    public function lastThread()
    {
        return $this->hasOne(Thread::class)->where('scrubbed', '=', '0')->latest();
    }

    public function blurb()
    {
        return $this->hasOne(Blurb::class, 'author_id')->where('scrubbed', '=', '0')->latest();
    }

    public function lastReply()
    {
        return $this->hasOne(Reply::class)->where('scrubbed', '=', '0')->latest();
    }

    public function owns(Item $item)
    {
        $get = Inventory::where('item_id', '=', "$item->id")->where('user_id', '=', "$this->id")->first();
        if($get)
        {
            return true;
        } else {
            return false;
        }
    }

    public function specials()
    {
        $get = Inventory::where('user_id', '=', $this->id)->where('special', '=', 1)->get();
        return $get;
    }

    public function grant_currency(int $amount, $type)
    {
        if($type == 1)
        {
            return $this->update(['cash' => $this->cash + $amount]);
        } elseif($type == 2) {
            return $this->update(['coins' => $this->coins + $amount]);
        }
    }

    public function revoke_currency(int $amount, $type)
    {
        if($type == 1)
        {
            return $this->update(['cash' => $this->cash - $amount]);
        } elseif($type == 2) {
            return $this->update(['coins' => $this->coins - $amount]);
        }
    }

    public function threadRead(Thread $thread)
    {
        $get = ThreadView::where('ip', '=', $_SERVER['REMOTE_ADDR'])->where('thread_id', '=', "$thread->id")->first();
        if($get)
        {
            return true;
        } else {
            return false;
        }
    }

    public function lastIp()
    {
        $lookup = Ip::where('user_id', '=', $_SERVER['REMOTE_ADDR'])->latest();

        return $lookup;
    }

    public function theme()
    {
        $theme = "";

        if($this->theme == 1 || $this->theme == 2)
        {
            $theme = "/static/css/bv-base/buildaverse.css?r=".mt_rand(100000, 999999);
        }

        return $theme;
    }

    public function get_avatar()
    {
        if($this->hasOne(Avatar::class)->exists())
        {
            $url = "https://cdn.buildaverse.net/". $this->avatar_url . ".png";
            return $url;
        } else {
            Avatar::create(['user_id' => $this->id]);
            $url = "https://cdn.buildaverse.net/". $this->avatar_url . ".png";
            return $url;
        }
    }

    public function avatar()
    {
        return $this->hasOne(Avatar::class, 'user_id');
    }

    public function get_short_num($num) {
        if ($num < 999) {
            return $num;
        }
        else if ($num > 999 && $num <= 9999) {
            $new_num = substr($num, 0, 1);
            return $new_num.'K+';
        }
        else if ($num > 9999 && $num <= 99999) {
            $new_num = substr($num, 0, 2);
            return $new_num.'K+';
        }
        else if ($num > 99999 && $num <= 999999) {
            $new_num = substr($num, 0, 3);
            return $new_num.'K+';
        }
        else if ($num > 999999 && $num <= 9999999) {
            $new_num = substr($num, 0, 1);
            return $new_num.'M+';
        }
        else if ($num > 9999999 && $num <= 99999999) {
            $new_num = substr($num, 0, 2);
            return $new_num.'M+';
        }
        else if ($num > 99999999 && $num <= 999999999) {
            $new_num = substr($num, 0, 3);
            return $new_num.'M+';
        }
        else {
            return $num;
        }
    }

    public function get_membership()
    {
        $membership = "";
        if($this->membership > 0)
        {
            if($this->membership == 1)
            {
                $membership = "Silver";
            } elseif($this->membership == 2) {
                $membership = "Gold";
            }
        }
        return $membership;
    }

    public function membership()
    {
        if($this->membership_expires != null)
        {
            if(!Carbon::parse($this->membership_expires)->isPast())
            {
                return true;
            } else {
                $this->update(['membership' => 0]);
                return false;
            }
        } else {
            return false;
        }
    }

    public function guilds()
    {
        $members = GuildMember::where('user_id', '=', $this->id)->get();
        $guilds = [];

        foreach ($members as $member)
            $guilds[] = $member->guild->id;

        return Guild::whereIn('id', $guilds)->get();
    }

    public function guildsCount()
    {
        return GuildMember::where('user_id', '=', auth()->user()->id)->count();
    }

    public function badges()
    {
        $badges = UserBadge::where('user_id', '=', $this->id)->get();
        $array = [];

        foreach ($badges as $badge) {
            $data = config('badges')[$badge->badge_id];

            $badge = new \stdClass;
            $badge->name = $data['name'];
            $badge->description = $data['description'];
            $badge->image = asset("img/badges/{$data['image']}.png");

            $array[] = $badge;
        }

        return $array;
    }

    public function ownsBadge($id)
    {
        return UserBadge::where([
            ['user_id', '=', $this->id],
            ['badge_id', '=', $id]
        ])->exists();
    }

    public function giveBadge($id, $granter = null)
    {
        $badge = new UserBadge;
        $badge->user_id = $this->id;
        $badge->granter_id = $granter;
        $badge->badge_id = $id;
        $badge->save();
    }

    public function removeBadge($id)
    {
        return UserBadge::where([
            ['user_id', '=', $this->id],
            ['badge_id', '=', $id]
        ])->delete();
    }

    public function forumLikes()
    {
        return $this->hasMany(ForumLike::class);
    }

    public function forumHasLiked($id)
    {
        return $this->hasOne(ForumLike::class)->where('user_id', $this->id)->where('target_id', $id)->exists();
    }

    public function hasLinkedDiscord()
    {
        return DiscordUser::where('user_id', '=', $this->id)->exists();
    }

    public function discord()
    {
        return $this->hasOne(DiscordUser::class)->where('user_id', $this->id);
    }
}
