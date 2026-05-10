<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $query = Medicine::with('availabilities');

        if ($request->filled('name')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%')
                  ->orWhere('active_substance', 'like', '%' . $request->name . '%');
            });
        }
        if ($request->filled('form'))         $query->where('form', $request->form);
        if ($request->filled('manufacturer')) $query->where('manufacturer', 'like', '%' . $request->manufacturer . '%');

        $medicines = $query->get();

        return response()->json($medicines->map(function ($m) use ($request) {
            $available = $m->availabilities->where('status', 'available');
            if ($request->filled('price_min')) $available = $available->where('price', '>=', $request->price_min);
            if ($request->filled('price_max')) $available = $available->where('price', '<=', $request->price_max);

            $status = $available->count() > 0 ? 'available' : 'unavailable';
            if ($request->filled('status') && $request->status !== $status) return null;

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
            ->orWhere('active_substance', 'like', "%{$q}%")->get());
    }

    public function forms()
    {
        return response()->json(Medicine::distinct()->pluck('form'));
    }

    public function show($id)
    {
        return response()->json(Medicine::with(['availabilities.pharmacy'])->findOrFail($id));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:100',
            'active_substance' => 'required|string|max:100',
            'form'             => 'required|string|max:50',
            'dose'             => 'required|string|max:50',
            'manufacturer'     => 'required|string|max:100',
            'description'      => 'nullable|string',
        ]);

        $medicine = Medicine::create($request->all());
        return response()->json($medicine, 201);
    }

    public function update(Request $request, $id)
    {
        $medicine = Medicine::findOrFail($id);
        $request->validate([
            'name'             => 'required|string|max:100',
            'active_substance' => 'required|string|max:100',
            'form'             => 'required|string|max:50',
            'dose'             => 'required|string|max:50',
            'manufacturer'     => 'required|string|max:100',
            'description'      => 'nullable|string',
        ]);

        $medicine->update($request->all());
        return response()->json($medicine);
    }

    public function destroy($id)
    {
        $medicine = Medicine::findOrFail($id);
        $medicine->delete();
        return response()->json(['message' => 'Zāles dzēstas veiksmīgi.']);
    }
}
