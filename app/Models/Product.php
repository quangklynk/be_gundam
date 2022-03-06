<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'id', 'name', 'price', 'discount','unit', 'description', 'remark', 'avatar', 'view', 'idCategory', 'flag'
    ];

    public function category() {
        return $this->belongsTo(Category::class, 'idCategory', 'id');
    }

    public function listImage(){
        return $this->hasMany(List_Image::class, 'idProduct', 'id');
    }

    public function rate(){
        return $this->hasMany(Rate::class, 'idProduct', 'id');
    }
}
