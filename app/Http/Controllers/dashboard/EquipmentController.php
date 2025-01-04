<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\StoreEquipmentRequest;
use App\Http\Requests\dashboard\UpdateEquipmentRequest;
use Illuminate\Http\Request;
use App\Models\Equipment;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipments = Equipment::paginate(10);
        $totalEquipments = $equipments->count();
        $availableEquipments = $equipments->where('status', 'available')->count();
        $maintenanceEquipments = $equipments->where('status', 'maintenance')->count();

        return view('equipments.index', compact('equipments', 'totalEquipments', 'availableEquipments', 'maintenanceEquipments'));
    }

    public function create()
    {
        return view('equipments.create');
    }

    public function store(StoreEquipmentRequest $request)
    {
        Equipment::create($request->validated());
        return redirect()->route('admin.equipments.index')->with('success', 'Equipment created successfully.');
    }

    public function edit(Equipment $equipment)
    {
        return view('equipments.edit', compact('equipment'));
    }

    public function show(Equipment $equipment)
    {
        
        return view('equipments.show', compact('equipment'));
    }


    public function update(UpdateEquipmentRequest $request, Equipment $equipment)
    {
        $equipment->update($request->validated());
        return redirect()->route('admin.equipments.index')->with('success','Equipment updated successfully.');
    }

    public function destroy(Equipment $equipment)
    {
        $equipment->delete();
        return redirect()->route('admin.equipments.index')->with('success','Equipment updated successfully.' );
    }
}
