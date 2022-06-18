<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;


class Thread extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $softDelete = true;

    protected $dates = [
        'created_at',
        'edited_at',
        'last_reply',
        'deleted_at'
    ];


    public function path(): string
    {
        return "/forum/thread/$this->id";
    }

    public function replies()
    {
        return $this->hasMany(Reply::class)->where('scrubbed', '=', '0');
    }

    public function likes()
    {
        return $this->hasMany(ForumLike::class, 'target_id')->where('target_type', '=', '1');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function addReply($reply)
    {
        $this->replies()->create($reply);
    }

    public function lock()
    {
        $this->update(['locked' => true]);
    }

    public function unlock()
    {
        $this->update(['locked' => false]);
    }

    public function pin()
    {
        $this->update(['pinned' => true]);
    }

    public function unpin()
    {
        $this->update(['pinned' => false]);
    }

    public function latestReply()
    {
        return $this->hasOne(Reply::class)->where('scrubbed', '=', '0')->latest();
    }
}
