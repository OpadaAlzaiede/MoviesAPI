<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'rate', 'date'];

    public function category() {

        return $this->belongsTo(Category::class);
    }

    public function rates() {

        return $this->hasMany(Rate::class);
    }
}
