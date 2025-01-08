<?php

namespace App\Http\Controllers\Api\member;

use App\Models\MealPlan;
use App\Http\Controllers\Controller;
use App\Http\Requests\member\SubscribeMealPlanRequest;

class MealPlanController extends Controller
{

    // Subscribe a user to a meal plan.

    public function subscribe(SubscribeMealPlanRequest $request)
{
    // Get the authenticated user
    $user = auth()->user();

    // Find the selected meal plan by ID
    $mealPlan = MealPlan::findOrFail($request->meal_plan_id);

    // Check if the user is already subscribed to a meal plan
    if ($mealPlan) {
        return $this->errorResponse('User is already subscribed to a meal plan.');
    }

    return $this->successResponse(null, 'Successfully subscribed to the meal plan.');
}

//--------------------------------------------------------------------------------//
    public function show($id)
    {
        // Get the authenticated user
        $user = auth()->user();

        // Check if the user exists
        if (!$user) {
            return $this->errorResponse('User not found.', 404);
        }
        $mealPlan = MealPlan::find($id);

        if (!$mealPlan) {
            return $this->errorResponse('meal plan not found.', 404);
        }
        // Return the meal plan
        return $this->successResponse($mealPlan, 'Your meal plan retrieved successfully.');
    }

//for the trainer
    // // Get the meal plans subscribed by a user.

    // public function getUserMealPlans($userId)
    // {
    //     $user = User::find($userId);
    //     if (!$user) {
    //         return $this->errorResponse('User not found.', 404);
    //     }

    //     $mealPlans = $user->mealPlans;

    //     return $this->successResponse($mealPlans, 'Meal plans retrieved successfully.');
    // }
}
