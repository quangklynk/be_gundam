<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $fillable = [
        'id', 'idUser', 'idStatus', 'note', 'address', 'idEmployee'
    ];

    public function status() {
        return $this->belongsTo(Status::class, 'idStatus', 'id');
    }

    public function detailOrder(){
        return $this->hasMany(Detail_Order::class, 'idOrder', 'id');
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'idUser', 'id');
    }

    public function rate(){
        return $this->hasMany(Rate::class, 'idOrder', 'id');
    }

    public function employee() {
        return $this->belongsTo(Employee::class, 'idEmployee', 'id');
    }
}
