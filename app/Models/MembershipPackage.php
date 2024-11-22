<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipPackage extends Model
{
    use HasFactory;

    protected $fillable = ['plan_id', 'name', 'price', 'duration_days', 'max_training_sessions', 'difficulty_level', 'description', 'is_active', 'meal_plan_id'];

    public function plan()
    {
        return $this->belongsTo(MembershipPlan::class);
    }

    public function mealPlan()
    {
        return $this->belongsTo(MealPlan::class);
    }

    public function userMemberships()
    {
        return $this->hasMany(UserMembership::class, 'package_id');
    }

    public function sessions()
    {
        return $this->hasMany(TrainingSession::class);
    }
}

