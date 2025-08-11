<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'quantity',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function dettaglioPezzi()
    {
        return $this->hasMany(ItemDetail::class);
    }

    // Helpers per conteggi
    public function getDisponibiliAttribute()
    {
        return $this->dettaglioPezzi()->where('stato', 'disponibile')->count();
    }
    public function getInUsoAttribute()
    {
        return $this->dettaglioPezzi()->where('stato', 'in_uso')->count();
    }
    public function getInAttesaAttribute()
    {
        return $this->dettaglioPezzi()->where('stato', 'in_attesa')->count();
    }
}
