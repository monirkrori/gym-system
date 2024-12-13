<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id', 'trainer_id', 'name', 'description', 'type', 'difficulty_level',
        'start_time', 'end_time', 'current_capacity', 'max_capacity',
        'allowed_membership_levels', 'status'
    ];

    public function package()
    {
        return $this->belongsTo(MembershipPackage::class);
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
