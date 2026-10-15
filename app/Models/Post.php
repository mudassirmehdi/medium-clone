<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
// use Spatie\Sluggable\HasSlug;
// use Spatie\Sluggable\SlugOptions;

class Post extends Model implements HasMedia
{
    use InteractsWithMedia;
    // use HasSlug;
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        // 'image',
        'category_id',
        'user_id',
        'published_at',
    ];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->width(400);

        $this
            ->addMediaConversion('large')
            ->width(1200);
    }

    // public function registerMediaCollections(): void
    // {
    //     $this->addMediaCollection('default')
    //         ->singleFile();
    // }

    /**
     * Get the options for generating the slug.
     */
    // public function getSlugOptions(): SlugOptions
    // {
    //     return SlugOptions::create()
    //         ->generateSlugsFrom('title')
    //         ->saveSlugsTo('slug');
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function claps()
    {
        return $this->hasMany(Clap::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function readTime($wordsPerMinute = 100)
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = ceil($wordCount / $wordsPerMinute);
        return max(1, $minutes);
    }

    // public function imageUrl($conversionName = '')
    // {
    //     $media = $this->getFirstMedia();
    //     if (!$media) {
    //         return null;
    //     }
    //     if ($media->hasGeneratedConversion($conversionName)) {
    //         return $media->getUrl($conversionName);
    //     }
    //     return $media->getUrl($conversionName);

    //     // return $this->getFirstMedia()->getUrl('preview');   
    // }


    // public function imageUrl(): ?string
    // {
    //     // 1. Prefer the uploaded avatar from Spatie.
    //     $media = $this->getFirstMedia('avatar');

    //     if ($media) {
    //         if ($media->hasGeneratedConversion('avatar')) {
    //             return $media->getUrl('avatar');
    //         }

    //         return $media->getUrl();
    //     }

    //     // 2. Fall back to the local demo avatar.
    //     if ($this->image) {
    //         if (filter_var($this->image, FILTER_VALIDATE_URL)) {
    //             return $this->image;
    //         }

    //         return \Illuminate\Support\Facades\Storage::disk('public')
    //             ->url($this->image);
    //     }

    //     // 3. No image available.
    //     return null;
    // }

    public function imageUrl($conversionName = '')
    {
        // 1. Prefer the image uploaded through Spatie Media Library.
        $media = $this->getFirstMedia();

        if ($media) {
            if (
                $conversionName !== '' &&
                $media->hasGeneratedConversion($conversionName)
            ) {
                return $media->getUrl($conversionName);
            }

            return $media->getUrl();
        }

        // 2. Fall back to the existing image stored on the public disk.
        if ($this->image) {
            // Return the image directly if it is already a URL.
            if (filter_var($this->image, FILTER_VALIDATE_URL)) {
                return $this->image;
            }

            // Return the Laravel public storage URL.
            return \Illuminate\Support\Facades\Storage::disk('public')
                ->url($this->image);
        }

        // 3. No image available.
        return null;
    }
}
