<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnterSticker extends Model
{
    protected $fillable = [
        'id', 'dateAdd', 'idEmployee', 'flag'
    ];
    public function detailEnterSticker(){
        return $this->hasMany(DetailEnterSticker::class, 'idSticker', 'id');
    }
    public function employee() {
        return $this->belongsTo(Employee::class, 'idEmployee', 'id');
    }
}
