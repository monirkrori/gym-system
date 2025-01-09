<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\MealPlanRequest;
use App\Models\Equipment;
use App\Models\MealPlan;
use App\Services\FilterService;
use Illuminate\Http\Request;



class MealPlanController extends Controller
{
    public function __construct(FilterService $filterService)
    {
        $this->filterService = $filterService;
    }

    public function index(Request $request)
    {

        $filters = $request->only(['status', 'name', 'category']);

        $query = Equipment::query();

        $this->filterService->applyFilters($query, $filters);

        $mealPlans = $query->paginate(10);

        $totalMealPlans = $query->count();

        return view('meal-plans.index', compact('mealPlans', 'totalMealPlans'));
    }

    public function create()
    {
        return view('meal-plans.create');
    }

    public function store(MealPlanRequest $request)
    {
        MealPlan::create($request->validated());
        return redirect()->route('admin.meal-plans.index')->with('success', 'Meal Plan created successfully.');
    }

    public function show(MealPlan $mealPlan)
    {
        return view('meal-plans.show', compact('mealPlan'));
    }


    public function edit(MealPlan $mealPlan)
    {
        return view('meal-plans.edit', compact('mealPlan'));
    }

    public function update(MealPlanRequest $request, MealPlan $mealPlan)
    {
        $mealPlan->update($request->validated());
        return redirect()->route('admin.meal-plans.index')->with('success', 'Meal Plan updated successfully.');
    }

    public function destroy(MealPlan $mealPlan)
    {
        $mealPlan->delete();
        return redirect()->route('admin.meal-plans.index')->with('success', 'Meal Plan deleted successfully.');
    }
}
