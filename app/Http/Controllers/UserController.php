<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // عرض الأعضاء
    public function index(Request $request)
    {
        $query = User::query();

        // البحث حسب اسم العضو
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // تصفية حسب حالة العضو (نشط أو غير نشط)
        if ($request->has('status') && $request->status != 'الكل') {
            $query->where('status', $request->status);
        }

        // جلب الأعضاء مع التصفية والبحث
        $members = $query->paginate(10);

        return view('members.index', compact('members'));
    }

    // عرض صفحة إضافة عضو جديد
    public function create()
    {
        return view('members.create');
    }

    // إضافة عضو جديد
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'subscription_type' => 'required|string|max:255',
            'expiration_date' => 'required|date',
            'status' => 'required|string',
        ]);

        User::create($request->all());

        return redirect()->route('members.index')->with('success', 'تم إضافة العضو بنجاح');
    }

    // عرض صفحة تعديل العضو
    public function edit($id)
    {
        $member = User::findOrFail($id);
        return view('members.edit', compact('member'));
    }

    // تحديث بيانات العضو
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'subscription_type' => 'required|string|max:255',
            'expiration_date' => 'required|date',
            'status' => 'required|string',
        ]);

        $member = User::findOrFail($id);
        $member->update($request->all());

        return redirect()->route('members.index')->with('success', 'تم تحديث العضو بنجاح');
    }

    // حذف العضو
    public function destroy($id)
    {
        $member = User::findOrFail($id);
        $member->delete();

        return redirect()->route('members.index')->with('success', 'تم حذف العضو بنجاح');
    }

    // تصدير البيانات
    public function export()
    {
        // يمكنك إضافة منطق التصدير هنا
        // على سبيل المثال تصدير البيانات إلى ملف Excel أو CSV
    }
}
