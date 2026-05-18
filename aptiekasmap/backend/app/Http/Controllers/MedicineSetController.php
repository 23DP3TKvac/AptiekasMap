<?php
namespace App\Http\Controllers;
use App\Models\MedicineSet;
use Illuminate\Http\Request;

class MedicineSetController extends Controller {
    public function index(Request $request) {
        return response()->json(
            MedicineSet::with('medicines')
                ->where('user_id', $request->user()->id)
                ->get()
        );
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
        ]);
        $set = MedicineSet::create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
            'description' => $request->description,
        ]);
        return response()->json($set, 201);
    }

    public function addMedicine(Request $request, $set_id) {
        $request->validate(['medicine_id' => 'required|exists:medicines,id']);
        $set = MedicineSet::where('id', $set_id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();
        $set->medicines()->syncWithoutDetaching([$request->medicine_id]);
        return response()->json($set->load('medicines'));
    }

    public function removeMedicine(Request $request, $set_id, $medicine_id) {
        $set = MedicineSet::where('id', $set_id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();
        $set->medicines()->detach($medicine_id);
        return response()->json($set->load('medicines'));
    }

    public function destroy(Request $request, $set_id) {
        MedicineSet::where('id', $set_id)
            ->where('user_id', $request->user()->id)
            ->delete();
        return response()->json(['message' => 'Komplekts dzēsts.']);
    }
}
