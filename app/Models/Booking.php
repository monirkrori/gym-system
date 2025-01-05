<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable=['user_id','session_id','status','booked_at'];

    protected $dates = ['booked_at', 'completed_at'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function Session()
    {
        return $this->belongsTo(TrainingSession::class, 'session_id');
    }
}
