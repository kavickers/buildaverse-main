<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $dates = ['created_at', 'updated_at'];

    protected $softDelete = true;

    protected $table = "reports";

    public function owner()
    {
        return $this->belongsTo(User::class, 'by');
    }
}
