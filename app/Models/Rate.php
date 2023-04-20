<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = ['id', 'ip', 'date'];

    public static function boot() {

        parent::boot();

        self::creating(function($rate) {

            $rate->date = Carbon::now();
        });
    }
}
