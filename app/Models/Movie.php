<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\AllowedFilter;

class Movie extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'rate'];

    public $timestamps = false;

    public function category() {

        return $this->belongsTo(Category::class);
    }

    public function rates() {

        return $this->hasMany(Rate::class);
    }

    public function attachments() {

        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function calcNewRate($rate) {

        $this->rate = ($this->rate + $rate) / 2;
        $this->save();
    }

    public static function allowedIncludes() {

        return [
            'category',
            'rates'
        ];
    }
    public static function allowedFilters() {

        return [
            'name',
            'category.name',
            AllowedFilter::exact('rate')
        ];
    }
}
