<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\TrainersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreTrainerRequest;
use App\Http\Requests\Dashboard\UpdateTrainerRequest;
use App\Models\Trainer;
use App\Models\User;
use App\Services\Dashboard\TrainerService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TrainerController extends Controller
{
    protected TrainerService $trainerService;

    public function __construct(TrainerService $trainerService)
    {
        $this->trainerService = $trainerService;
    }

    /**
     * Display a listing of the trainers with filters and pagination.
     */
    public function index(Request $request)
    {
        $this->authorize('view-trainer');

        // Get filters from request
        $filters = $request->only(['search', 'status']);

        // Fetch trainers with filters and pagination using the TrainerService
        $data = $this->trainerService->getTrainersWithFilters($filters);

        // Pass the data to the view
        return view('trainers.index', $data);
    }

    /**
     * Show the details of a specific trainer.
     */
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
     * Store a newly created trainer in the database.
     */
    public function store(StoreTrainerRequest $request)
    {
        try {
            $this->trainerService->createTrainer($request->validated());
            return redirect()->route('admin.trainers.index')->with('success', 'Trainer created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
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
     * Update the specified trainer in the database.
     */
    public function update(UpdateTrainerRequest $request, Trainer $trainer)
    {
        $this->trainerService->updateTrainer($trainer, $request->validated());
        return redirect()->route('admin.trainers.index')->with('success', 'Trainer updated successfully.');
    }

    /**
     * Remove the specified trainer from the database.
     */
    public function destroy(Trainer $trainer)
    {
        $this->authorize('admin.delete-trainer');
        $this->trainerService->deleteTrainer($trainer);
        return redirect()->route('admin.trainers.index')->with('success', 'Trainer deleted successfully.');
    }

    /**
     * Export trainers data as PDF or Excel.
     */
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

    /**
     * Export trainers data as PDF.
     */
    private function exportAsPdf()
    {
        $trainers = Trainer::all();
        $pdf = Pdf::loadView('trainers.pdf', compact('trainers'));
        return $pdf->download('trainers.pdf');
    }

    /**
     * Export trainers data as Excel.
     */
    private function exportAsExcel()
    {
        return Excel::download(new TrainersExport, 'trainers.xlsx');
    }
}
