<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'seriale',
        'colore',
        'ram',
        'altro',
        'stato',
        'data_inizio_uso',
        'data_fine_uso',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function usages()
    {
        return $this->hasMany(\App\Models\ItemDetailUsage::class, 'item_detail_id');
    }
}
