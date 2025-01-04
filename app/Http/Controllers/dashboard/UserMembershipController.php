<?php

namespace App\Http\Controllers\dashboard;

use App\Exports\UserMembershipsExport;
use App\Exports\UserMembershipsPdfExport;
use App\Http\Controllers\Controller;
use App\Models\MembershipPackage;
use App\Models\MembershipPlan;
use App\Models\Trainer;
use App\Models\User;
use App\Models\UserMembership;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use function redirect;
use function view;


class UserMembershipController extends Controller
{
    // عرض جميع العضويات
    public function index()
    {
        // إحصائيات العضويات
        $totalMembers = UserMembership::count(); // إجمالي العضويات
        $activeMembers = UserMembership::where('status', 'active')->count(); // العضويات النشطة
        $expiredMembers = UserMembership::where('status', 'expired')->count(); // العضويات المنتهية

        // جلب العضويات مع بيانات المستخدم والباقات
        $memberships = UserMembership::with(['user', 'package','plan'])->paginate(10);

        return view('memberships.index', compact('memberships', 'totalMembers', 'activeMembers', 'expiredMembers'));
    }

    // عرض صفحة إنشاء عضوية جديدة
    public function create()
    {
        $this->authorize('create-membership');

        $users = User::all();
        $plans = MembershipPlan::all();
        $packages = MembershipPackage::all();

        return view('memberships.create', compact('users', 'plans', 'packages'));
    }

    // حفظ عضوية جديدة
    public function store(Request $request)
    {
        $this->authorize('create-membership');

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|',
            'package_id' => 'required|',
            'start_date' => 'required',
        ]);



        $plan = MembershipPlan::find($request->plan_id);
        $package = MembershipPackage::find($request->package_id);
        $endDate = Carbon::parse($request->start_date)->addMonths($plan->duration_month);
        $user = User::find($request->user_id);

        $membership = UserMembership::where('user_id', $user->id)->first();
        $trainer = Trainer::where('user_id', $user->id)->first();
        if ($trainer ){
            $trainer->delete();
            UserMembership::create([
                'user_id' => $request->user_id,
                'package_id' => $request->package_id,
                'plan_id' => $request->plan_id,
                'start_date' => $request->start_date,
                'end_date' => $endDate,
                'remaining_sessions' => $package->max_training_sessions,
            ]);

            $user->removeRole('trainer');
            $user->assignRole('member');
        }

        elseif($membership){
            return redirect()->back()->with('error', 'العضو موجود مسبقاً في النادي');
        }
        else{
            UserMembership::create([
                'user_id' => $request->user_id,
                'package_id' => $request->package_id,
                'plan_id' => $request->plan_id,
                'start_date' => $request->start_date,
                'end_date' => $endDate,
                'remaining_sessions' => $package->max_training_sessions,
            ]);
        }



        return redirect()->route('admin.memberships.index')->with('success', 'تم إنشاء العضوية بنجاح!');
    }
    // عرض تفاصيل عضوية
    public function show(UserMembership $membership)
    {
        return view('memberships.show', compact('membership'));
    }

    // عرض صفحة تعديل العضوية
    public function edit(UserMembership $membership)
    {

        $users = User::all();
        $plans = MembershipPlan::all();
        $packages = MembershipPackage::all();

        return view('memberships.edit', compact('membership', 'users', 'plans', 'packages'));
    }

    // تحديث بيانات العضوية
    public function update(Request $request, UserMembership $membership)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        $membership->update($validated);

        return redirect()->route('admin.memberships.index')->with('success', 'تم تحديث العضوية بنجاح!');
    }

    // حذف العضوية
    public function destroy(UserMembership $membership)
    {
        $membership->delete();

        return redirect()->route('admin.memberships.index')->with('success', 'تم حذف العضوية بنجاح!');
    }

    public function exportExcel()
    {
        return Excel::download(new UserMembershipsExport, 'memberships.xlsx');
    }

    public function exportPdf()
    {
        $exporter = new UserMembershipsPdfExport();
        return $exporter->export();
    }
}
