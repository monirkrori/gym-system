<?php

namespace App\Http\Controllers\dashboard;
use App\Http\Controllers\Controller;

use App\Http\Requests\dashboard\MembershipPackageRequest;
use App\Models\MembershipPackage;
use Illuminate\Http\Request;

class MembershipPackageController extends Controller
{

    public function index()
    {
        $membershipPackages = MembershipPackage::paginate(10);
        $totalMembershipPackages  = MembershipPackage::count();
        return view('membership-packages.index', compact('membershipPackages','totalMembershipPackages' ));
    }

    public function create()
    {
        return view('membership-packages.create');
    }

    public function store(MembershipPackageRequest $request)
    {
        MembershipPackage::create($request->validated());
        return redirect()->route('admin.membership-packages.index')->with('success', 'membership Package created successfully.');
    }

    public function show(MembershipPackage $membershipPackage)
    {
        return view('membership-packages.show', compact('membershipPackage'));
    }



    public function edit(MembershipPackage $membershipPackage)
    {
        return view('membership-packages.edit', compact('membershipPackage'));
    }


    public function update(MembershipPackageRequest $request, MembershipPackage $membershipPackage)
    {
        $membershipPackage->update($request->validated());
        return redirect()->route('admin.membership-packages.index')->with('success', 'membership Package updated successfully.');
    }


    public function destroy(MembershipPackage $membershipPackage)
    {
        $membershipPackage->delete();
        return redirect()->route('admin.membership-packages.index')->with('success', 'membership Package deleted successfully.');
    }
}
