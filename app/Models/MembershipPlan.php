<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MembershipPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'price', 'duration_month', 'description', 'is_active'];

    public function package()
    {
        return $this->hasOne(MembershipPackage::class);
    }

    public function userMemberships()
    {
        return $this->hasMany(UserMembership::class);
    }

}
