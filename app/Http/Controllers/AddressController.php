<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Division;
use App\Models\District;
use App\Models\Upazila;
use App\Models\Union;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Address::class);
        $addresses = Auth::user()->addresses()->orderBy('is_default', 'desc')->get();
        return view('profile.addresses.index', compact('addresses'));
    }

    public function create()
    {
        $this->authorize('create', Address::class);
        $divisions = Division::all();
        return view('profile.addresses.create', compact('divisions'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Address::class);

        $validated = $request->validate([
            'label' => 'nullable|string|max:255',
            'division_id' => 'required_without:manual_division|exists:divisions,id',
            'district_id' => 'required_without:manual_district|exists:districts,id',
            'upazila_id' => 'nullable|exists:upazilas,id',
            'union_id' => 'nullable|exists:unions,id',
            'street_address' => 'nullable|string',
            'postal_code' => 'nullable|string|max:20',
            'is_default' => 'boolean',
            'manual_division' => 'nullable|string|max:255|required_without:division_id',
            'manual_district' => 'nullable|string|max:255|required_without:district_id',
            'manual_upazila' => 'nullable|string|max:255',
            'manual_union' => 'nullable|string|max:255',
        ]);

        $addressData = [
            'user_id' => Auth::id(),
            'label' => $validated['label'],
            'division' => $validated['manual_division'] ?? Division::find($validated['division_id'])->name,
            'district' => $validated['manual_district'] ?? District::find($validated['district_id'])->name,
            'upazila' => $validated['manual_upazila'] ?? ($validated['upazila_id'] ? Upazila::find($validated['upazila_id'])->name : null),
            'union' => $validated['manual_union'] ?? ($validated['union_id'] ? Union::find($validated['union_id'])->name : null),
            'street_address' => $validated['street_address'],
            'postal_code' => $validated['postal_code'],
            'is_default' => $validated['is_default'] ?? false,
        ];

        if ($addressData['is_default']) {
            Auth::user()->addresses()->update(['is_default' => false]);
        }

        Address::create($addressData);

        return redirect()->route('profile.addresses.index')
            ->with('success', 'Address added successfully.');
    }

    public function edit(Address $address)
    {
        $this->authorize('update', $address);
        $divisions = Division::all();
        $districts = $address->division_id ? District::where('division_id', $address->division_id)->get() : collect();
        $upazilas = $address->district_id ? Upazila::where('district_id', $address->district_id)->get() : collect();
        $unions = $address->upazila_id ? Union::where('upazila_id', $address->upazila_id)->get() : collect();

        return view('profile.addresses.edit', compact('address', 'divisions', 'districts', 'upazilas', 'unions'));
    }

    public function update(Request $request, Address $address)
    {
        $this->authorize('update', $address);

        $validated = $request->validate([
            'label' => 'nullable|string|max:255',
            'division_id' => 'required_without:manual_division|exists:divisions,id',
            'district_id' => 'required_without:manual_district|exists:districts,id',
            'upazila_id' => 'nullable|exists:upazilas,id',
            'union_id' => 'nullable|exists:unions,id',
            'street_address' => 'nullable|string',
            'postal_code' => 'nullable|string|max:20',
            'is_default' => 'boolean',
            'manual_division' => 'nullable|string|max:255|required_without:division_id',
            'manual_district' => 'nullable|string|max:255|required_without:district_id',
            'manual_upazila' => 'nullable|string|max:255',
            'manual_union' => 'nullable|string|max:255',
        ]);

        $address->update([
            'label' => $validated['label'],
            'division' => $validated['manual_division'] ?? Division::find($validated['division_id'])->name,
            'district' => $validated['manual_district'] ?? District::find($validated['district_id'])->name,
            'upazila' => $validated['manual_upazila'] ?? ($validated['upazila_id'] ? Upazila::find($validated['upazila_id'])->name : null),
            'union' => $validated['manual_union'] ?? ($validated['union_id'] ? Union::find($validated['union_id'])->name : null),
            'street_address' => $validated['street_address'],
            'postal_code' => $validated['postal_code'],
            'is_default' => $validated['is_default'] ?? false,
        ]);

        if ($address->is_default) {
            Auth::user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        return redirect()->route('profile.addresses.index')
            ->with('success', 'Address updated successfully.');
    }

    public function destroy(Address $address)
    {
        $this->authorize('delete', $address);
        $address->delete();
        return redirect()->route('profile.addresses.index')
            ->with('success', 'Address deleted successfully.');
    }

    public function setDefault(Address $address)
    {
        $this->authorize('update', $address);
        Auth::user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);
        return redirect()->route('profile.addresses.index')
            ->with('success', 'Default address updated successfully.');
    }

    public function getDistricts($divisionId)
    {
        return response()->json(District::where('division_id', $divisionId)->get());
    }

    public function getUpazilas($districtId)
    {
        return response()->json(Upazila::where('district_id', $districtId)->get());
    }

    public function getUnions($upazilaId)
    {
        return response()->json(Union::where('upazila_id', $upazilaId)->get());
    }
}
