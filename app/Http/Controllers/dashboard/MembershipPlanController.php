<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\MembershipPlanRequest;
use App\Models\MembershipPlan;
use Illuminate\Http\Request;

class MembershipPlanController extends Controller

    {
        public function index(){
        $membershipPlans = MembershipPlan::paginate(10);
        $totalmembershipPlans = MembershipPlan::count();

        return view('membership-plans.index', compact('membershipPlans', 'totalmembershipPlans'));
    }

    public function create()
    {
        return view('membership-plans.create');
    }

    public function store(MembershipPlanRequest $request)
    {
        MembershipPlan::create($request->validated());
        return redirect()->route('admin.membership-plans.index')->with('success', 'Membership Plan created successfully.');
    }


    public function edit(MembershipPlan $membershipPlan)
    {
        return view('membership-plans.edit', compact('membershipPlan'));
    }

    public function update(MembershipPlanRequest $request, MembershipPlan $membershipPlan)
    {
        $membershipPlan->update($request->validated());
        return redirect()->route('admin.membership-plans.index')->with('success', 'Membership Plan updated successfully.');
    }

    public function destroy(MembershipPlan $membershipPlan)
    {
        $membershipPlan->delete();
        return redirect()->route('admin.membership-plans.index')->with('success', 'Membership Plan deleted successfully.');
    }
    }
