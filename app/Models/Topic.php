<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;


class Topic extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $softDelete = true;

    public function threads()
    {
        return $this->hasMany(Thread::class)->where('scrubbed', '=', '0');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class)->where('scrubbed', '=', '0');
    }

    public function latestThread()
    {
        return $this->hasOne(Thread::class)->where('scrubbed', '=', '0')->latest();
    }

    public function latestReply()
    {
        return $this->hasOne(Reply::class)->where('scrubbed', '=', '0')->latest();
    }
}
