<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Feed extends Model
{
    use HasFactory;

    protected $fillable = ["name", "url", "rss_path", "image"];

    /**
     * @param $value
     */
    public function setUrlAttribute($value)
    {
        $this->attributes["url"] = trim(strtolower($value));
    }

    /**
     * @param $value
     */
    public function setRssPathAttribute($value)
    {
        $this->attributes["rss_path"] = trim(strtolower($value));
    }

    /**
     * @param $value
     */
    public function setImageAttribute($value)
    {
        $this->attributes["image"] = trim(strtolower($value));
    }

    /**
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
