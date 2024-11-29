<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMembership extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'package_id', 'start_date', 'end_date', 'remaining_sessions', 'status','plan_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(MembershipPackage::class);
    }

    public function plans()
    {
        return $this->belongsTo(MembershipPlan::class);
    }


}
