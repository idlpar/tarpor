<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\District;
use App\Models\Upazila;
use App\Models\Union;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Address::class);
        $addresses = Auth::user()->addresses()->orderBy('is_default', 'desc')->get();
        return view('profile.addresses.index', compact('addresses'));
    }

    public function create()
    {
        $this->authorize('create', Address::class);
        // Divisions are no longer used
        return view('profile.addresses.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Address::class);

        $validated = $request->validate([
            'label' => 'nullable|string|max:255',
            'district' => 'required|string|max:255',
            'upazila' => 'required|string|max:255',
            'union' => 'required|string|max:255',
            'street_address' => 'required|string',
            'postal_code' => 'required|string|max:20',
            'is_default' => 'boolean',
        ]);

        $addressData = [
            'user_id' => Auth::id(),
            'label' => $validated['label'],
            'district' => $validated['district'],
            'upazila' => $validated['upazila'],
            'union' => $validated['union'],
            'street_address' => $validated['street_address'],
            'postal_code' => $validated['postal_code'],
            'is_default' => $validated['is_default'] ?? false,
        ];

        if ($addressData['is_default']) {
            Auth::user()->addresses()->update(['is_default' => false]);
        }

        $address = Address::create($addressData);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Address added successfully.', 'address' => $address]);
        }

        return redirect()->route('profile.addresses.index')
            ->with('success', 'Address added successfully.');
    }

    public function edit(Address $address)
    {
        $this->authorize('update', $address);
        // Divisions are no longer used
        $districts = $address->district_id ? District::where('district_id', $address->district_id)->get() : collect(); // Adjusted
        $upazilas = $address->district_id ? Upazila::where('district_id', $address->district_id)->get() : collect();
        $unions = $address->upazila_id ? Union::where('upazila_id', $address->upazila_id)->get() : collect();

        return view('profile.addresses.edit', compact('address', 'districts', 'upazilas', 'unions'));
    }

    public function update(Request $request, Address $address)
    {
        $this->authorize('update', $address);

        $validated = $request->validate([
            'label' => 'nullable|string|max:255',
            'district' => 'required|string|max:255',
            'upazila' => 'required|string|max:255',
            'union' => 'required|string|max:255',
            'street_address' => 'required|string',
            'postal_code' => 'required|string|max:20',
            'is_default' => 'boolean',
        ]);

        $addressData = [
            'label' => $validated['label'],
            'district' => $validated['district'],
            'upazila' => $validated['upazila'],
            'union' => $validated['union'],
            'street_address' => $validated['street_address'],
            'postal_code' => $validated['postal_code'],
            'is_default' => $validated['is_default'] ?? false,
        ];

        $address->update($addressData);

        if ($address->is_default) {
            Auth::user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Address updated successfully.', 'address' => $address]);
        }

        return redirect()->route('profile.addresses.index')
            ->with('success', 'Address updated successfully.');
    }

    public function destroy(Address $address)
    {
        if (Auth::id() !== $address->user_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        // Prevent deletion if it's the only address or the only default address
        $userAddresses = Auth::user()->addresses;
        if ($userAddresses->count() === 1) {
            return response()->json(['success' => false, 'message' => 'You cannot delete your only address.'], 400);
        }

        if ($address->is_default && $userAddresses->where('is_default', true)->count() === 1) {
            // If this is the only default address, and there are other addresses, set another as default
            $otherAddress = $userAddresses->where('id', '!=', $address->id)->first();
            if ($otherAddress) {
                $otherAddress->is_default = true;
                $otherAddress->save();
            }
        }

        $address->delete();

        return response()->json(['success' => true, 'message' => 'Address deleted successfully.']);
    }

    public function setDefault(Address $address, Request $request)
    {
        $this->authorize('update', $address);
        Auth::user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Default address updated successfully.']);
        }

        return redirect()->route('profile.addresses.index')
            ->with('success', 'Default address updated successfully.');
    }

    // Removed getDistricts method
    // public function getDistricts($divisionId)
    // {
    //     return response()->json(District::where('division_id', $divisionId)->get());
    // }

    public function getUpazilas($districtId)
    {
        return response()->json(Upazila::where('district_id', $districtId)->get());
    }

    public function getUnions($upazilaId)
    {
        return response()->json(Union::where('upazila_id', $upazilaId)->get());
    }

    // New methods for API
    public function getUserAddresses(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 401);
        }
        $addresses = Auth::user()->addresses()->orderBy('is_default', 'desc')->get();
        $defaultAddress = $addresses->where('is_default', true)->first();
        return response()->json(['success' => true, 'addresses' => $addresses, 'defaultAddress' => $defaultAddress]);
    }

    public function showUserAddress(Address $address, Request $request)
    {
        $this->authorize('view', $address); // Ensure user owns the address
        return response()->json(['success' => true, 'address' => $address]);
    }
}
