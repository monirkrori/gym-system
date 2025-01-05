<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'rateable_id', 'rateable_type', 'rating', 'comment', 'parent_id', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define a relationship for the nested replies
    public function parent()
    {
        return $this->belongsTo(Rating::class, 'parent_id');
    }
    
    public function rateable()
    {
        return $this->morphTo();
    }

    public function replies()
    {
        return $this->hasMany(Rating::class, 'parent_id');
    }
}
