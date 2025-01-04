<?php

namespace App\Http\Controllers\dashboard;

use App\Exports\TrainersExport;
use App\Http\Controllers\Controller;
use App\Models\Trainer;
use App\Models\User;
use App\Models\UserMembership;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use function abort;
use function view;

class TrainerController extends Controller
{
    public function index()
    {
        $this->authorize('view-trainer');

        $trainers = Trainer::with('user')->paginate(10);
        $totalTrainers = Trainer::count(); // العدد الكلي للمدربين
        $activeTrainer = Trainer::where('status', 'available')->count();

        return view('trainers.index', compact('trainers', 'totalTrainers', 'activeTrainer'));
    }
    public function show(Trainer $trainer)
    {
        $this->authorize('view-trainer');

        return view('trainers.show', compact('trainer'));
    }

    /**
     * Show the form for creating a new trainer.
     */
    public function create()
    {
        $this->authorize('create-trainer');
        $users = User::all();
        return view('trainers.create', compact('users'));
    }

    /**
     * Store a newly created trainer in storage.
     */
    public function store(Request $request)
    {

        $this->authorize('create-trainer');
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'specialization' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'status' => 'required|in:available,unavailable',
            'rating_avg' => 'nullable|numeric|min:0|max:5',
        ]);

        $user = User::find($request->user_id);

        $membership = UserMembership::where('user_id', $user->id)->first();
        $trainer = Trainer::where('user_id', $user->id)->first();

        if ($membership) {
            $membership->delete();
            Trainer::create($request->all());
            $user->removeRole('member');
            $user->assignRole('trainer');
        } elseif ($trainer) {
            return redirect()->back()->with('error', 'المدرب موجود مسبقاً في النادي');
        } else {
            Trainer::create($request->all());
        }


        return redirect()->route('admin.trainers.index')->with('success', 'تم إضافة المدرب بنجاح');
    }

    /**
     * Show the form for editing the specified trainer.
     */
    public function edit(Trainer $trainer)
    {
        $this->authorize('edit-trainer');
        $users = User::all();

        return view('trainers.edit', compact('trainer', 'users'));
    }

    /**
     * Update the specified trainer in storage.
     */
    public function update(Request $request, Trainer $trainer)
    {
        $this->authorize('edit-trainer'); // صلاحية تعديل مدرب

        $validated = $request->validate([
            'specialization' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'status' => 'required|in:available,unavailable',
            'rating_avg' => 'nullable|numeric|min:0|max:5',
        ]);

        $trainer->update($validated);

        return redirect()->route('admin.trainers.index')->with('success', 'تم تحديث المدرب بنجاح.');
    }

    /**
     * Remove the specified trainer from storage.
     */
    public function destroy(Trainer $trainer)
    {
        $this->authorize('admin.delete-trainer'); // صلاحية حذف مدرب

        $trainer->delete();

        return redirect()->route('admin.trainers.index')->with('success', 'تم حذف المدرب بنجاح.');
    }

    public function export($type)
    {
        switch ($type) {
            case 'pdf':
                return $this->exportAsPdf();
            case 'excel':
                return $this->exportAsExcel();
            default:
                abort(404, 'نوع التصدير غير مدعوم');
        }
    }

    private function exportAsPdf()
    {
        $trainers = Trainer::all();
        $pdf = Pdf::loadView('trainers.pdf', compact('trainers'));
        return $pdf->download('trainers.pdf');
    }

    private function exportAsExcel()
    {

        return Excel::download(new TrainersExport, 'trainers.xlsx');
    }

}
