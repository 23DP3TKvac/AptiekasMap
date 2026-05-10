<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $query = Medicine::with('availabilities');

        // Filter by name
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%')
                  ->orWhere('active_substance', 'like', '%' . $request->name . '%');
        }

        // Filter by form (tabletes, kapsulas, etc.)
        if ($request->filled('form')) {
            $query->where('form', $request->form);
        }

        // Filter by manufacturer
        if ($request->filled('manufacturer')) {
            $query->where('manufacturer', 'like', '%' . $request->manufacturer . '%');
        }

        $medicines = $query->get();

        return response()->json($medicines->map(function ($m) use ($request) {
            $available = $m->availabilities->where('status', 'available');

            // Filter by price range
            if ($request->filled('price_min')) {
                $available = $available->where('price', '>=', $request->price_min);
            }
            if ($request->filled('price_max')) {
                $available = $available->where('price', '<=', $request->price_max);
            }

            // Filter by status
            $status = $available->count() > 0 ? 'available' : 'unavailable';
            if ($request->filled('status') && $request->status !== $status) {
                return null;
            }

            return [
                'id'               => $m->id,
                'name'             => $m->name,
                'active_substance' => $m->active_substance,
                'form'             => $m->form,
                'dose'             => $m->dose,
                'manufacturer'     => $m->manufacturer,
                'description'      => $m->description,
                'status'           => $status,
                'minPrice'         => $available->count() > 0 ? number_format($available->min('price'), 2) : '—',
                'pharmacyCount'    => $available->count(),
            ];
        })->filter()->values());
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

    public function forms()
    {
        $forms = Medicine::distinct()->pluck('form');
        return response()->json($forms);
    }
}
