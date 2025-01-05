<?php

namespace App\Http\Controllers\dashboard;

use App\Exports\UserMembershipsExport;
use App\Exports\UserMembershipsPdfExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\UserMemberShipRequest;
use App\Models\MembershipPackage;
use App\Models\MembershipPlan;
use App\Models\User;
use App\Models\UserMembership;
use App\Services\UserMembershipService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserMembershipController extends Controller
{
    private $membershipService;

    public function __construct(UserMembershipService $membershipService)
    {
        $this->membershipService = $membershipService;
    }

    public function index()
    {
        $statistics = $this->membershipService->getMembershipStatistics();
        $memberships = $this->membershipService->getMembershipsPaginated();
        $totalMembers = UserMembership::count();
        $activeMembers = UserMembership::where('status', 'active')->count();
        $expiredMembers = UserMembership::where('status', 'expired')->count();

        return view('memberships.index', compact('memberships', 'statistics','totalMembers','activeMembers','expiredMembers'));
    }

    public function create()
    {
        $this->authorize('create-membership');
        return view('memberships.create', [
            'users' => User::all(),
            'plans' => MembershipPlan::where('status', 'active')->get(),
            'packages' => MembershipPackage::where('status', 'active')->get(),
        ]);
    }

    public function store(UserMemberShipRequest $request)
    {
        $this->authorize('create-membership');

        try {
            $this->membershipService->createMembership($request->validated());
            return redirect()->route('admin.memberships.index')->with('success', 'تم إنشاء العضوية بنجاح!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show(UserMembership $membership)
    {
        return view('memberships.show', compact('membership'));
    }

    public function edit(UserMembership $membership)
    {
        return view('memberships.edit', [
            'membership' => $membership,
            'users' => User::all(),
            'plans' => MembershipPlan::where('status', 'active')->get(),
            'packages' => MembershipPackage::where('status', 'active')->get()
        ]);
    }

    public function update(UserMemberShipRequest $request, UserMembership $membership)
    {
        $this->membershipService->updateMembership($membership, $request->validated());
        return redirect()->route('admin.memberships.index')->with('success', 'تم تحديث العضوية بنجاح!');
    }

    public function destroy(UserMembership $membership)
    {
        $this->membershipService->deleteMembership($membership);
        return redirect()->route('admin.memberships.index')->with('success', 'تم حذف العضوية بنجاح!');
    }

    public function exportExcel()
    {
        return Excel::download(new UserMembershipsExport, 'memberships.xlsx');
    }

    public function exportPdf()
    {
        return (new UserMembershipsPdfExport())->export();
    }
}
