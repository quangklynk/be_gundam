<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'id', 'name', 'address', 'image','idUser', 'phone'
    ];
    public function order(){
        return $this->hasMany(Orders::class, 'idUser', 'id');
    }
}
