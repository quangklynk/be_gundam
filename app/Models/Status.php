<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = [
        'id', 'description'
    ];
    public function order(){
        return $this->hasMany(Orders::class, 'idStatus', 'id');
    }
}
