<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailEnterSticker extends Model
{
    protected $fillable = [
        'idSticker', 'idProduct', 'unit', 'price'
    ];
    public function sticker() {
        return $this->belongsTo(EnterSticker::class, 'idSticker', 'id');
    }
}
