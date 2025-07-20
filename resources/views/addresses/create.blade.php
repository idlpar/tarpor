@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-bg-light py-8">
        @include('components.breadcrumbs', [
            'links' => [
                'My Addresses' => route('profile.addresses.index'),
                'Add New Address' => null
            ]
        ])
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg p-6 md:p-8">
                <h2 class="text-3xl font-bold text-text-dark mb-6">Add New Address</h2>

                <form method="POST" action="{{ route('profile.addresses.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="label" class="block text-sm font-medium text-text-dark">Label (Home, Office, etc.)</label>
                            <input id="label" type="text" class="mt-1 block w-full rounded-md border-input-border bg-input-bg text-text-dark shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 p-2 @error('label') border-error @enderror" name="label" value="{{ old('label') }}">
                            @error('label')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-text-dark">Division</label>
                            <select name="division_id" id="division" class="mt-1 block w-full rounded-md border-input-border bg-input-bg text-text-dark shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 p-2 @error('division_id') border-error @enderror" required>
                                <option value="">Select Division</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>{{ $division->name }}</option>
                                @endforeach
                            </select>
                            @error('division_id')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <small class="text-text-light mt-2 block">Or enter manually:</small>
                            <input type="text" name="manual_division" id="manual_division" class="mt-2 block w-full rounded-md border-input-border bg-input-bg text-text-dark shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 p-2" value="{{ old('manual_division') }}" placeholder="Enter division manually">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-text-dark">District</label>
                            <select name="district_id" id="district" class="mt-1 block w-full rounded-md border-input-border bg-input-bg text-text-dark shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 p-2 @error('district_id') border-error @enderror" disabled required>
                                <option value="">Select District</option>
                            </select>
                            @error('district_id')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <small class="text-text-light mt-2 block">Or enter manually:</small>
                            <input type="text" name="manual_district" id="manual_district" class="mt-2 block w-full rounded-md border-input-border bg-input-bg text-text-dark shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 p-2" value="{{ old('manual_district') }}" placeholder="Enter district manually">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-text-dark">Upazila/Thana</label>
                            <select name="upazila_id" id="upazila" class="mt-1 block w-full rounded-md border-input-border bg-input-bg text-text-dark shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 p-2 @error('upazila_id') border-error @enderror" disabled>
                                <option value="">Select Upazila/Thana</option>
                            </select>
                            @error('upazila_id')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <small class="text-text-light mt-2 block">Or enter manually:</small>
                            <input type="text" name="manual_upazila" id="manual_upazila" class="mt-2 block w-full rounded-md border-input-border bg-input-bg text-text-dark shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 p-2" value="{{ old('manual_upazila') }}" placeholder="Enter upazila/thana manually">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-text-dark">Union/Ward</label>
                            <select name="union_id" id="union" class="mt-1 block w-full rounded-md border-input-border bg-input-bg text-text-dark shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 p-2 @error('union_id') border-error @enderror" disabled>
                                <option value="">Select Union/Ward</option>
                            </select>
                            @error('union_id')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <small class="text-text-light mt-2 block">Or enter manually:</small>
                            <input type="text" name="manual_union" id="manual_union" class="mt-2 block w-full rounded-md border-input-border bg-input-bg text-text-dark shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 p-2" value="{{ old('manual_union') }}" placeholder="Enter union/ward manually">
                        </div>

                        <div>
                            <label for="street_address" class="block text-sm font-medium text-text-dark">Street Address</label>
                            <textarea id="street_address" class="mt-1 block w-full rounded-md border-input-border bg-input-bg text-text-dark shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 p-2 @error('street_address') border-error @enderror" name="street_address">{{ old('street_address') }}</textarea>
                            @error('street_address')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-text-dark">Postal Code</label>
                            <input id="postal_code" type="text" class="mt-1 block w-full rounded-md border-input-border bg-input-bg text-text-dark shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 p-2 @error('postal_code') border-error @enderror" name="postal_code" value="{{ old('postal_code') }}">
                            @error('postal_code')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <div class="flex items-center">
                                <input class="h-4 w-4 text-primary focus:ring-primary border-input-border rounded" type="checkbox" name="is_default" id="is_default" {{ old('is_default') ? 'checked' : '' }}>
                                <label class="ml-2 block text-sm text-text-dark" for="is_default">
                                    Set as default address
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors duration-200">
                            Save Address
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const divisionSelect = document.getElementById('division');
            const districtSelect = document.getElementById('district');
            const upazilaSelect = document.getElementById('upazila');
            const unionSelect = document.getElementById('union');

            const manualDivision = document.getElementById('manual_division');
            const manualDistrict = document.getElementById('manual_district');
            const manualUpazila = document.getElementById('manual_upazila');
            const manualUnion = document.getElementById('manual_union');

            // Toggle between select and manual input
            [divisionSelect, manualDivision].forEach(el => {
                el.addEventListener('input', function() {
                    if (this.value && this === manualDivision) {
                        divisionSelect.value = '';
                        districtSelect.disabled = true;
                        upazilaSelect.disabled = true;
                        unionSelect.disabled = true;
                    } else if (this.value && this === divisionSelect) {
                        manualDivision.value = '';
                        loadDistricts(this.value);
                    }
                });
            });

            [districtSelect, manualDistrict].forEach(el => {
                el.addEventListener('input', function() {
                    if (this.value && this === manualDistrict) {
                        districtSelect.value = '';
                        upazilaSelect.disabled = true;
                        unionSelect.disabled = true;
                    } else if (this.value && this === districtSelect) {
                        manualDistrict.value = '';
                        loadUpazilas(this.value);
                    }
                });
            });

            [upazilaSelect, manualUpazila].forEach(el => {
                el.addEventListener('input', function() {
                    if (this.value && this === manualUpazila) {
                        upazilaSelect.value = '';
                        unionSelect.disabled = true;
                    } else if (this.value && this === upazilaSelect) {
                        manualUpazila.value = '';
                        loadUnions(this.value);
                    }
                });
            });

            [unionSelect, manualUnion].forEach(el => {
                el.addEventListener('input', function() {
                    if (this.value && this === unionSelect) {
                        manualUnion.value = '';
                    }
                });
            });

            // Load districts when division is selected
            function loadDistricts(divisionId) {
                fetch(`/api/districts/${divisionId}`)
                    .then(response => response.json())
                    .then(districts => {
                        districtSelect.innerHTML = '<option value="">Select District</option>';
                        districts.forEach(district => {
                            districtSelect.innerHTML += `<option value="${district.id}">${district.name}</option>`;
                        });
                        districtSelect.disabled = false;
                        upazilaSelect.innerHTML = '<option value="">Select Upazila/Thana</option>';
                        upazilaSelect.disabled = true;
                        unionSelect.innerHTML = '<option value="">Select Union/Ward</option>';
                        unionSelect.disabled = true;
                    });
            }

            // Load upazilas when district is selected
            function loadUpazilas(districtId) {
                fetch(`/api/upazilas/${districtId}`)
                    .then(response => response.json())
                    .then(upazilas => {
                        upazilaSelect.innerHTML = '<option value="">Select Upazila/Thana</option>';
                        upazilas.forEach(upazila => {
                            upazilaSelect.innerHTML += `<option value="${upazila.id}">${upazila.name}</option>`;
                        });
                        upazilaSelect.disabled = false;
                        unionSelect.innerHTML = '<option value="">Select Union/Ward</option>';
                        unionSelect.disabled = true;
                    });
            }

            // Load unions when upazila is selected
            function loadUnions(upazilaId) {
                fetch(`/api/unions/${upazilaId}`)
                    .then(response => response.json())
                    .then(unions => {
                        unionSelect.innerHTML = '<option value="">Select Union/Ward</option>';
                        unions.forEach(union => {
                            unionSelect.innerHTML += `<option value="${union.id}">${union.name}</option>`;
                        });
                        unionSelect.disabled = false;
                    });
            }

            // If there was a validation error, try to load the previous selections
            @if(old('division_id'))
            loadDistricts({{ old('division_id') }});
            setTimeout(() => {
                districtSelect.value = {{ old('district_id', 'null') }};
                @if(old('district_id'))
                loadUpazilas({{ old('district_id') }});
                setTimeout(() => {
                    upazilaSelect.value = {{ old('upazila_id', 'null') }};
                    @if(old('upazila_id'))
                    loadUnions({{ old('upazila_id') }});
                    setTimeout(() => {
                        unionSelect.value = {{ old('union_id', 'null') }};
                    }, 300);
                    @endif
                }, 300);
                @endif
            }, 300);
            @endif
        });
    </script>
@endsection
