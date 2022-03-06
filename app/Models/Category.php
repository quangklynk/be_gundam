<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function distributor() {
        return $this->belongsTo(Distributor::class, 'idDistributor', 'id');
    }

    public function product(){
        return $this->hasMany(Product::class, 'idCategory', 'id');
    }
}
