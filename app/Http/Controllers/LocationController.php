<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Upazila;
use App\Models\Union;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getDistricts()
    {
        $districts = District::all();
        return response()->json($districts);
    }

    public function getUpazilas($districtId)
    {
        $upazilas = Upazila::where('district_id', $districtId)->get();
        return response()->json($upazilas);
    }

    public function getUnions($upazilaId)
    {
        $unions = Union::where('upazila_id', $upazilaId)->get();
        return response()->json($unions);
    }
}
