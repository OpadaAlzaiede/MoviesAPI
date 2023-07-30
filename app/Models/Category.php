<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model
{
    use HasFactory, HasSlug;

    public $timestamps = false;

    protected $guarded = ['id'];

    public static function boot() {

        parent::boot();

        self::deleting(function($category) {

            $attachments = $category->attachments()->get();

            foreach($attachments as $attachment) {

                Storage::delete($attachment->path);
            }
        });
    }

    public function movies() {

        return $this->hasMany(Movie::class);
    }

    public function attachments() {

        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function getSlugOptions()
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public static function allowedIncludes() {

        return [
            'movies',
        ];
    }
    public static function allowedFilters() {

        return [
            'name',
        ];
    }
}
