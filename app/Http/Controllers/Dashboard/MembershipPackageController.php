<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\MembershipPackageRequest;
use App\Models\MembershipPackage;
use App\Services\FilterService;
use Illuminate\Http\Request;

/**
 * Controller for managing Membership Packages in the dashboard.
 *
 * This controller handles CRUD operations for membership packages,
 * along with filtering capabilities using the FilterService.
 */
class MembershipPackageController extends Controller
{
    /**
     * @var FilterService
     */
    protected $filterService;

    /**
     * MembershipPackageController constructor.
     *
     * @param FilterService $filterService
     */
    public function __construct(FilterService $filterService)
    {
        $this->filterService = $filterService;
    }

    /**
     * Display a paginated list of membership packages with filtering.
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        $filters = $request->only(['status', 'name']);


        // Use the FilterService to apply filters on the MembershipPackage model
        $query = MembershipPackage::query();
        $this->filterService->applyFilters($query, $filters);

        $membershipPackages = $query->paginate(10);

        // Count total membership packages (ignoring filters)
        $totalMembershipPackages = MembershipPackage::count();

        return view('membership-packages.index', compact('membershipPackages', 'totalMembershipPackages'));
    }

    /**
     * Show the form for creating a new membership package.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('membership-packages.create');
    }

    /**
     * Store a newly created membership package in storage.
     *
     * @param MembershipPackageRequest $request
     */
    public function store(MembershipPackageRequest $request)
    {
        MembershipPackage::create($request->validated());
        return redirect()
            ->route('admin.membership-packages.index')
            ->with('success', 'Membership package created successfully.');
    }

    /**
     * Display the specified membership package.
     *
     * @param MembershipPackage $membershipPackage
     */
    public function show(MembershipPackage $membershipPackage)
    {
        return view('membership-packages.show', compact('membershipPackage'));
    }

    /**
     * Show the form for editing the specified membership package.
     *
     * @param MembershipPackage $membershipPackage
     */
    public function edit(MembershipPackage $membershipPackage)
    {
        return view('membership-packages.edit', compact('membershipPackage'));
    }

    /**
     * Update the specified membership package in storage.
     *
     * @param MembershipPackageRequest $request
     * @param MembershipPackage $membershipPackage
     */
    public function update(MembershipPackageRequest $request, MembershipPackage $membershipPackage)
    {
        $membershipPackage->update($request->validated());
        return redirect()
            ->route('admin.membership-packages.index')
            ->with('success', 'Membership package updated successfully.');
    }

    /**
     * Remove the specified membership package from storage.
     *
     * @param MembershipPackage $membershipPackage
     */
    public function destroy(MembershipPackage $membershipPackage)
    {
        $membershipPackage->delete();
        return redirect()
            ->route('admin.membership-packages.index')
            ->with('success', 'Membership package deleted successfully.');
    }
}
