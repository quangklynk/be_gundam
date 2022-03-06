<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
    public function category(){
        return $this->hasMany(Category::class, 'idDistributor', 'id');
    }
}
