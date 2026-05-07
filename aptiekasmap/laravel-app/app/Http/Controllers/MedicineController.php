<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index()
    {
        $medicines = Medicine::with('availabilities')->get();

        return response()->json($medicines->map(function ($m) {
            $available = $m->availabilities->where('status', 'available');
            return [
                'id'               => $m->id,
                'name'             => $m->name,
                'active_substance' => $m->active_substance,
                'form'             => $m->form,
                'dose'             => $m->dose,
                'manufacturer'     => $m->manufacturer,
                'description'      => $m->description,
                'status'           => $available->count() > 0 ? 'available' : 'unavailable',
                'minPrice'         => $available->count() > 0 ? number_format($available->min('price'), 2) : '—',
                'pharmacyCount'    => $available->count(),
            ];
        }));
    }

    public function search(Request $request)
    {
        $q = $request->query('q', '');
        return response()->json(Medicine::where('name', 'like', "%{$q}%")
            ->orWhere('active_substance', 'like', "%{$q}%")
            ->get());
    }

    public function show($id)
    {
        return response()->json(
            Medicine::with(['availabilities.pharmacy'])->findOrFail($id)
        );
    }
}
