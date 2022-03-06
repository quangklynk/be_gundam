<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'id', 'titleBlog', 'content', 'image','idEmployee',
    ];

    public function employee() {
        return $this->belongsTo(Employee::class, 'idEmployee', 'id');
    }
}
