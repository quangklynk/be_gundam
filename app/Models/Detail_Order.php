<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detail_Order extends Model
{
    protected $fillable = [
        'idOrder', 'idProduct', 'unit', 'price'
    ];

    public function order() {
        return $this->belongsTo(Orders::class, 'idOrder', 'id');
    }
}
