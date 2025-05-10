<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasFactory;
    use HasSlug;
    use SoftDeletes;

    protected $fillable = ['title', 'description', 'image', 'price', 'stock', 'image_mime', 'image_size', 'created_by', 'updated_by', 'status'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getEncodedImageUrlAttribute(): string
    {
        $path = parse_url($this->image, PHP_URL_PATH);
        $filename = basename($path);
        $encodedName = rawurlencode($filename);

        return str_replace($filename, $encodedName, $this->image);
    }


    public function getRouteKeyName()
    {
        return 'slug';
    }
}
