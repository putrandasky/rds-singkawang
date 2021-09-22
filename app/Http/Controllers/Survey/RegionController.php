<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Models;

class RegionController extends Controller
{
    public function getRegencies($province_id)
    {
        $regencies = Models\Regency::where('province_id', $province_id)->get();
        return $regencies;
    }
}
