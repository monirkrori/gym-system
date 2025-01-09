<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Models\MembershipPlan;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\MembershipPlanRequest;
use App\Services\FilterService;

/**
 * Controller for managing Membership Plans in the dashboard.
 *
 * This controller handles CRUD operations for membership plans,
 * with support for filtering using the FilterService.
 */
class MembershipPlanController extends Controller
{
    /**
     * @var FilterService
     */
    protected $filterService;

    /**
     * MembershipPlanController constructor.
     *
     * @param FilterService $filterService
     */
    public function __construct(FilterService $filterService)
    {
        $this->filterService = $filterService;
    }

    /**
     * Display a paginated list of membership plans with filtering.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $filters = $request->only(['status', 'name']);

        // Apply filters using the FilterService
        $query = MembershipPlan::query();
         $this->filterService->applyFilters($query,$filters);

        // Paginate the filtered results
        $membershipPlans = $query->paginate(10);

        // Count total membership plans (ignoring filters)
        $totalMembershipPlans = MembershipPlan::count();

        return view('membership-plans.index', compact('membershipPlans', 'totalMembershipPlans'));
    }

    /**
     * Show the form for creating a new membership plan.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('membership-plans.create');
    }

    /**
     * Store a newly created membership plan in storage.
     *
     * @param MembershipPlanRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(MembershipPlanRequest $request)
    {
        MembershipPlan::create($request->validated());
        return redirect()
            ->route('admin.membership-plans.index')
            ->with('success', 'Membership Plan created successfully.');
    }

    /**
     * Show the form for editing the specified membership plan.
     *
     * @param MembershipPlan $membershipPlan
     * @return \Illuminate\View\View
     */
    public function edit(MembershipPlan $membershipPlan)
    {
        return view('membership-plans.edit', compact('membershipPlan'));
    }

    /**
     * Update the specified membership plan in storage.
     *
     * @param MembershipPlanRequest $request
     * @param MembershipPlan $membershipPlan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(MembershipPlanRequest $request, MembershipPlan $membershipPlan)
    {
        $membershipPlan->update($request->validated());
        return redirect()
            ->route('admin.membership-plans.index')
            ->with('success', 'Membership Plan updated successfully.');
    }

    /**
     * Display the specified membership plan.
     *
     * @param MembershipPlan $membershipPlan
     * @return \Illuminate\View\View
     */
    public function show(MembershipPlan $membershipPlan)
    {
        return view('membership-plans.show', compact('membershipPlan'));
    }

    /**
     * Remove the specified membership plan from storage.
     *
     * @param MembershipPlan $membershipPlan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(MembershipPlan $membershipPlan)
    {
        $membershipPlan->delete();
        return redirect()
            ->route('admin.membership-plans.index')
            ->with('success', 'Membership Plan deleted successfully.');
    }
}
