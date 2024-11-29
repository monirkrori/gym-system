<?php

namespace App\Http\Controllers\admin;

use App\Exports\UserMembershipsExport;
use App\Exports\UserMembershipsPdfExport;
use App\Http\Controllers\Controller;
use App\Models\MembershipPackage;
use App\Models\MembershipPlan;
use App\Models\User;
use App\Models\UserMembership;
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
        $memberships = UserMembership::with(['user', 'package'])->paginate(10);

        return view('memberships.index', compact('memberships', 'totalMembers', 'activeMembers', 'expiredMembers'));
    }

    // عرض صفحة إنشاء عضوية جديدة
    public function create()
    {
        $this->authorize('create-membership');

        // جلب المستخدمين والباقات لإنشاء العضوية
        $users = User::all();
        $packages = MembershipPlan::all();

        return view('memberships.create', compact('users', 'packages'));
    }

    // حفظ عضوية جديدة
    public function store(Request $request)
    {
        $this->authorize('create-membership');

        // التحقق من البيانات
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'package_id' => 'required|',
            'status' => 'required|in:active,expired',
        ]);

        // إنشاء العضوية
        UserMembership::create($validated);

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
        return view('memberships.edit', compact('membership'));
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

        return redirect()->route('memberships.index')->with('success', 'تم تحديث العضوية بنجاح!');
    }

    // حذف العضوية
    public function destroy(UserMembership $membership)
    {
        $membership->delete();

        return redirect()->route('memberships.index')->with('success', 'تم حذف العضوية بنجاح!');
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
