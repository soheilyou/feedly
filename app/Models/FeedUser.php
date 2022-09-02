<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FeedUser extends Pivot
{
    use HasFactory;

    protected $fillable = ["user_id", "feed_id"];
}
