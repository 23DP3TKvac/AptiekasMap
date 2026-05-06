<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    public function index()
    {
        return response()->json(Pharmacy::all());
    }

    public function show($id)
    {
        $pharmacy = Pharmacy::with(['availabilities.medicine'])->findOrFail($id);
        return response()->json($pharmacy);
    }
}
