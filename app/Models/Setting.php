<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'contact_email',
        'phone_number',
        'address',
        'about',
        'logo',
        'favicon',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'youtube_url',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Accessors
    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/settings/' . $this->logo) : asset('images/logo.png');
    }

    public function getFaviconUrlAttribute()
    {
        return $this->favicon ? asset('storage/settings/' . $this->favicon) : asset('images/favicon.ico');
    }

    // Methods
    public static function getSettings()
    {
        return static::first() ?? new static();
    }

    public function updateSettings(array $data)
    {
        $this->fill($data);
        $this->save();

        return $this;
    }
}