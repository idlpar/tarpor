@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Add New Address</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.addresses.store') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="label" class="col-md-4 col-form-label text-md-right">Label (Home, Office, etc.)</label>
                                <div class="col-md-6">
                                    <input id="label" type="text" class="form-control @error('label') is-invalid @enderror" name="label" value="{{ old('label') }}">
                                    @error('label')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right">Division</label>
                                <div class="col-md-6">
                                    <select name="division_id" id="division" class="form-control @error('division_id') is-invalid @enderror" required>
                                        <option value="">Select Division</option>
                                        @foreach($divisions as $division)
                                            <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>{{ $division->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('division_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <small class="text-muted">Or enter manually:</small>
                                    <input type="text" name="manual_division" id="manual_division" class="form-control mt-2" value="{{ old('manual_division') }}" placeholder="Enter division manually">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right">District</label>
                                <div class="col-md-6">
                                    <select name="district_id" id="district" class="form-control @error('district_id') is-invalid @enderror" disabled required>
                                        <option value="">Select District</option>
                                    </select>
                                    @error('district_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <small class="text-muted">Or enter manually:</small>
                                    <input type="text" name="manual_district" id="manual_district" class="form-control mt-2" value="{{ old('manual_district') }}" placeholder="Enter district manually">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right">Upazila/Thana</label>
                                <div class="col-md-6">
                                    <select name="upazila_id" id="upazila" class="form-control @error('upazila_id') is-invalid @enderror" disabled>
                                        <option value="">Select Upazila/Thana</option>
                                    </select>
                                    @error('upazila_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <small class="text-muted">Or enter manually:</small>
                                    <input type="text" name="manual_upazila" id="manual_upazila" class="form-control mt-2" value="{{ old('manual_upazila') }}" placeholder="Enter upazila/thana manually">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right">Union/Ward</label>
                                <div class="col-md-6">
                                    <select name="union_id" id="union" class="form-control @error('union_id') is-invalid @enderror" disabled>
                                        <option value="">Select Union/Ward</option>
                                    </select>
                                    @error('union_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <small class="text-muted">Or enter manually:</small>
                                    <input type="text" name="manual_union" id="manual_union" class="form-control mt-2" value="{{ old('manual_union') }}" placeholder="Enter union/ward manually">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="street_address" class="col-md-4 col-form-label text-md-right">Street Address</label>
                                <div class="col-md-6">
                                    <textarea id="street_address" class="form-control @error('street_address') is-invalid @enderror" name="street_address">{{ old('street_address') }}</textarea>
                                    @error('street_address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="postal_code" class="col-md-4 col-form-label text-md-right">Postal Code</label>
                                <div class="col-md-6">
                                    <input id="postal_code" type="text" class="form-control @error('postal_code') is-invalid @enderror" name="postal_code" value="{{ old('postal_code') }}">
                                    @error('postal_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_default" id="is_default" {{ old('is_default') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_default">
                                            Set as default address
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Save Address
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
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
