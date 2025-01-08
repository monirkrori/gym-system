<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'description',
        'user_id',
        'type_name',
        'type_color',
        'status_name',
        'status_color',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
