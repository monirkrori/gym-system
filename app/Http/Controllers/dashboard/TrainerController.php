<?php

namespace App\Http\Controllers\dashboard;

use App\Exports\TrainersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\StoreTrainerRequest;
use App\Http\Requests\dashboard\UpdateTrainerRequest;
use App\Models\Trainer;
use App\Models\User;
use App\Services\TrainerService;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class TrainerController extends Controller
{
protected TrainerService $trainerService;

public function __construct(TrainerService $trainerService)
{
$this->trainerService = $trainerService;
}

public function index()
{
$this->authorize('view-trainer');

$trainers = Trainer::with('user')->paginate(10);
$totalTrainers = Trainer::count();
$activeTrainer = Trainer::where('status', 'available')->count();

return view('trainers.index', compact('trainers', 'totalTrainers', 'activeTrainer'));
}

public function show(Trainer $trainer)
{
$this->authorize('view-trainer');
return view('trainers.show', compact('trainer'));
}

public function create()
{
$this->authorize('create-trainer');
$users = User::all();
return view('trainers.create', compact('users'));
}

public function store(StoreTrainerRequest $request)
{
try {
$this->trainerService->createTrainer($request->validated());
return redirect()->route('admin.trainers.index')->with('success', 'Trainer created successfully.');
} catch (\Exception $e) {
return redirect()->back()->with('error', $e->getMessage());
}
}

public function edit(Trainer $trainer)
{
$this->authorize('edit-trainer');
$users = User::all();
return view('trainers.edit', compact('trainer', 'users'));
}

public function update(UpdateTrainerRequest $request, Trainer $trainer)
{
$this->trainerService->updateTrainer($trainer, $request->validated());
return redirect()->route('admin.trainers.index')->with('success', 'Trainer updated successfully.');
}

public function destroy(Trainer $trainer)
{
$this->authorize('admin.delete-trainer');
$trainer->delete();
return redirect()->route('admin.trainers.index')->with('success', 'Trainer deleted successfully.');
}

public function export($type)
{
switch ($type) {
case 'pdf':
return $this->exportAsPdf();
case 'excel':
return $this->exportAsExcel();
default:
abort(404, 'Unsupported export type');
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
