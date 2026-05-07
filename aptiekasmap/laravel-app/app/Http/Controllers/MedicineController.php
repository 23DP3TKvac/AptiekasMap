<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index()
    {
        return response()->json(Medicine::all());
    }

    public function search(Request $request)
    {
        $q = $request->query('q', '');

        $medicines = Medicine::where('name', 'like', "%{$q}%")
            ->orWhere('active_substance', 'like', "%{$q}%")
            ->get();

        return response()->json($medicines);
    }

    public function show($id)
    {
        $medicine = Medicine::with(['availabilities.pharmacy'])->findOrFail($id);
        return response()->json($medicine);
    }
}
