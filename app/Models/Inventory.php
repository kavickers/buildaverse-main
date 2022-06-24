<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'type',
        'collection_number',
        'special',
        'can_trade',
        'can_open',
        'crate_id',
    ];

    public function onsale()
    {
        return ItemReseller::where('inventory_id', '=', $this->id)->exists();
    }
}
