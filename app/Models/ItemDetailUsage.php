<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemDetailUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_detail_id',
        'user_id',
        'request_id',
        'data_inizio',
        'data_fine',
    ];

    public function itemDetail()
    {
        return $this->belongsTo(ItemDetail::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function request()
    {
        return $this->belongsTo(Request::class);
    }
}
