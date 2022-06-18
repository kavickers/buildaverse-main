<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'user_transactions';

    protected $fillable = [
        'user_id',
        'source_id',
        'source_type',
        'type',
        'cash',
        'coins',
    ];

    /**
     *
     * Transaction types
     * 1 = Purchase
     * 2 = Sale
     * 3 = Guild Payout
     * 4 = Membership Stipend
     * 5 = Currency Purchases
     *
     * Source types
     * 1 = Item
     * 2 = Guild
     * 3 = Game
     *
     */
}
