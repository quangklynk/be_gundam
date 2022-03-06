<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class List_Image extends Model
{
    public function product() {
        return $this->belongsTo(Product::class, 'idProduct', 'id');
    }

    protected $fillable = [
        'id', 'image', 'idProduct', 'updated_at'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
