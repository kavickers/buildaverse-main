<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guild extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'name',
        'desc',
        'cash',
        'coins',
        'thumbnail_url'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function thumbnail()
    {
        return "1";
    }

    public function members()
    {
        return GuildMember::where('guild_id', '=', $this->id)->get();
    }

    public function ranks()
    {
        return GuildRank::where('guild_id', '=', $this->id)->orderBy('rank', 'ASC')->get();
    }
}
