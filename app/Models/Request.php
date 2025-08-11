<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    /**
     * Gli stati possibili per una richiesta.
     */
    public const STATI = [
        'in_attesa',
        'confermata',
        'annullata',
    ];

    protected $fillable = [
        'user_id',
        'item_detail_id',
        'data_inizio',
        'data_fine',
        'note',
        'stato',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function itemDetail() {
        return $this->belongsTo(ItemDetail::class);
    }
}
