<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Image\Transformations\Crop;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, InteractsWithMedia;

    protected $fillable = [
        'name',
        'username',
        'image',
        'country',
        'bio',
        'email',
        'password'
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('avatar')
            ->width(128)
            ->crop(128, 128);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile();
    }


    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Users that this user is following.
     */
    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }


    public function isFollowedBy(?User $user)
    {
        if (!$user) {
            return false;
        }
        return $this->followers()->where('follower_id', $user->id)->exists();
    }

    public function hasClapped(Post $post)
    {
        return $post->claps()->where('user_id', $this->id)->exists();
    }

    // public function imageUrl()
    // {
    //     $media = $this->getFirstMedia('avatar');
    //     if (!$media) {
    //         return null;
    //     }
    //     if ($media->hasGeneratedConversion('avatar')) {
    //         return $media->getUrl('avatar');
    //     }
    //     return $media->getUrl();
    // }

    // public function imageUrl(): ?string
    // {
    //     // Prefer the uploaded avatar managed by Spatie.
    //     $media = $this->getFirstMedia('avatar');

    //     if ($media) {
    //         if ($media->hasGeneratedConversion('avatar')) {
    //             return $media->getUrl('avatar');
    //         }

    //         return $media->getUrl();
    //     }

    //     // Fall back to the local demo avatar.
    //     if ($this->image) {
    //         return \Illuminate\Support\Facades\Storage::disk('public')
    //             ->url($this->image);
    //     }

    //     return null;
    // }

    public function imageUrl(): ?string
    {
        // 1. Check for an avatar uploaded through Spatie Media Library.
        $media = $this->getFirstMedia('avatar');

        if ($media) {
            // Use the avatar conversion if it exists.
            if ($media->hasGeneratedConversion('avatar')) {
                return $media->getUrl('avatar');
            }

            // Otherwise, use the original Spatie image.
            return $media->getUrl();
        }

        // 2. Fall back to an image stored on Laravel's public disk.
        if ($this->image) {
            return \Illuminate\Support\Facades\Storage::disk('public')
                ->url($this->image);
        }

        // 3. No image found.
        return null;
    }


    public function countryCode(): ?string
    {
        $countries = [
            'Pakistan' => 'pk',
            'United States' => 'us',
            'United Kingdom' => 'gb',
            'Canada' => 'ca',
            'India' => 'in',
            'Australia' => 'au',
            'Germany' => 'de',
            'France' => 'fr',
            'United Arab Emirates' => 'ae',
            'Saudi Arabia' => 'sa',
            'Czech Republic' => 'cz',
            'Czechia' => 'cz',
            'South Korea' => 'kr',
            'North Korea' => 'kp',
            'Russia' => 'ru',
            'China' => 'cn',
            'Japan' => 'jp',
            'Brazil' => 'br',
            'Mexico' => 'mx',
            'Spain' => 'es',
            'Italy' => 'it',
            'Netherlands' => 'nl',
            'Switzerland' => 'ch',
            'Sweden' => 'se',
            'Norway' => 'no',
            'New Zealand' => 'nz',
            'South Africa' => 'za',
            'Turkey' => 'tr',
            'Türkiye' => 'tr',
            'Indonesia' => 'id',
            'Malaysia' => 'my',
            'Singapore' => 'sg',
            'Bangladesh' => 'bd',
            'Nepal' => 'np',
            'Sri Lanka' => 'lk',
            'Afghanistan' => 'af',
            'Iran' => 'ir',
            'Iraq' => 'iq',
            'Egypt' => 'eg',
            'Nigeria' => 'ng',
            'Ireland' => 'ie',
            'Portugal' => 'pt',
            'Poland' => 'pl',
            'Ukraine' => 'ua',
            'Belgium' => 'be',
            'Denmark' => 'dk',
            'Finland' => 'fi',
            'Greece' => 'gr',
            'Israel' => 'il',
            'Jamaica' => 'jm',
            'Philippines' => 'ph',
            'Thailand' => 'th',
            'Vietnam' => 'vn',
            'Qatar' => 'qa',
            'Kuwait' => 'kw',
            'Oman' => 'om',
            'Bahrain' => 'bh',
            'Jordan' => 'jo',
            'Lebanon' => 'lb',
            'Palestine' => 'ps',
            'Taiwan' => 'tw',
            'Hong Kong' => 'hk',
            'Puerto Rico' => 'pr',
            'Iceland' => 'is',
            'Luxembourg' => 'lu',
            'Romania' => 'ro',
            'Hungary' => 'hu',
            'Argentina' => 'ar',
            'Chile' => 'cl',
            'Colombia' => 'co',
            'Peru' => 'pe',
            'Venezuela' => 've',
            'Kenya' => 'ke',
            'Ethiopia' => 'et',
            'Ghana' => 'gh',
            'Morocco' => 'ma',
            'Tunisia' => 'tn',
            'Algeria' => 'dz',
            'Libya' => 'ly',
            'Sudan' => 'sd',
            'Tanzania' => 'tz',
            'Uganda' => 'ug',
            'Zimbabwe' => 'zw',
            'Zambia' => 'zm',
        ];

        $country = trim($this->country ?? '');

        foreach ($countries as $name => $code) {
            if (strcasecmp($name, $country) === 0) {
                return $code;
            }
        }

        return null;
    }
}
