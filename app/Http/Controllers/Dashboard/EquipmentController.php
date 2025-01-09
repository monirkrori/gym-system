<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreEquipmentRequest;
use App\Http\Requests\Dashboard\UpdateEquipmentRequest;
use App\Models\Equipment;
use App\Services\FilterService;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    // Declare a protected property for the filter service to handle filtering logic
    protected $filterService;

    /**
     * Constructor method to initialize the FilterService.
     *
     * @param FilterService $filterService The filter service instance.
     */
    public function __construct(FilterService $filterService)
    {
        $this->filterService = $filterService;
    }

    /**
     * Display a listing of equipment with filters and pagination.
     *
     * This method retrieves and filters equipment records based on the request filters,
     * and then returns the equipment data to the view along with total counts.
     *
     * @param Request $request The HTTP request instance containing filter parameters.
     */
    public function index(Request $request)
    {
        // Retrieve the filters from the request
        $filters = $request->only(['status', 'name']);

        $query = Equipment::query();

        $this->filterService->applyFilters($query, $filters);

        $equipments = $query->paginate(10);

        // Calculate the total number of equipment and counts based on status
        $totalEquipments = $query->count();
        $availableEquipments = $query->where('status', 'available')->count();
        $maintenanceEquipments = $query->where('status', 'maintenance')->count();

        // Return the view with the retrieved data
        return view('equipments.index', compact(
            'equipments',
            'totalEquipments',
            'availableEquipments',
            'maintenanceEquipments'
        ));
    }

    /**
     * Show the form to create a new equipment.
     *
     * @return \Illuminate\View\View The view to create new equipment.
     */
    public function create()
    {
        return view('equipments.create');
    }

    /**
     * Store a newly created equipment record in the database.
     *
     * This method validates the request data and creates a new equipment record.
     *
     * @param StoreEquipmentRequest $request The request containing validated data.
     */
    public function store(StoreEquipmentRequest $request)
    {
        Equipment::create($request->validated());

        return redirect()->route('admin.equipments.index')->with('success', 'Equipment created successfully.');
    }

    /**
     * Show the form to edit an existing equipment record.
     *
     * @param Equipment $equipment The equipment instance to edit.
     */
    public function edit(Equipment $equipment)
    {
        return view('equipments.edit', compact('equipment'));
    }

    /**
     * Display the details of a specific equipment.
     *
     * @param Equipment $equipment The equipment instance to show.
     */
    public function show(Equipment $equipment)
    {
        return view('equipments.show', compact('equipment'));
    }

    /**
     * Update the specified equipment record in the database.
     *
     * This method validates the request data and updates the specified equipment record.
     *
     * @param UpdateEquipmentRequest $request The request containing validated data.
     * @param Equipment $equipment The equipment instance to update.
     */
    public function update(UpdateEquipmentRequest $request, Equipment $equipment)
    {
        $equipment->update($request->validated());

        // Redirect to the equipment listing page with a success message
        return redirect()->route('admin.equipments.index')->with('success', 'Equipment updated successfully.');
    }

    /**
     * Remove the specified equipment record from the database.
     *
     * This method deletes the specified equipment record.
     *
     */
    public function destroy(Equipment $equipment)
    {
        $equipment->delete();

        return redirect()->route('admin.equipments.index')->with('success', 'Equipment deleted successfully.');
    }
}
