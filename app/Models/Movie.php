<?php

namespace App\Models;

use Carbon\Carbon;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movie extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'rate'];

    public $timestamps = false;

    public static function boot() {

        parent::boot();

        self::deleting(function($movie) {

            $attachments = $movie->attachments()->get();

            foreach($attachments as $attachment) {

                Storage::delete($attachment->path);
            }

            $movie->rates()->delete();
        });
    }

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
