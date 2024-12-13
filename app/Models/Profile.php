<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use  SoftDeletes;


    protected $fillable = [
        'user_id',
        'phone_number',
        'address',
        'date_of_birth',
        'profile_image',
    ];


    // العلاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}




