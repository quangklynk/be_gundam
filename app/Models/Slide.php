<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    protected $fillable = [
        'id', 'image', 'idEmployee'
    ];
    public function employee() {
        return $this->belongsTo(Employee::class, 'idEmployee', 'id');
    }
}
