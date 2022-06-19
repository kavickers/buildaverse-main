<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumLike extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'target_id',
        'target_type',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }
}
