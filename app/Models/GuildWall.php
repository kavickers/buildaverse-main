<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuildWall extends Model
{
    use HasFactory;

    protected $table = 'guilds_wall';

    protected $fillable = [
        'user_id',
        'guild_id',
        'body'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
