<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = [
        'idOrder', 'idProduct', 'idCustomer', 'vote'
    ];
    public function product() {
        return $this->belongsTo(Product::class, 'idProduct', 'id');
    }
    public function order() {
        return $this->belongsTo(Orders::class, 'idOrder', 'id');
    }
}
