<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tshirt_id',
        'shirt_id',
        'pants_id',
        'hat1_id',
        'hat2_id',
        'hat3_id',
        'tool_id',
        'hex_head',
        'hex_torso',
        'hex_larm',
        'hex_rarm',
        'hex_lleg',
        'hex_rleg',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}