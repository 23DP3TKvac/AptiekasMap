<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function byMedicine($medicine_id)
    {
        $availability = Availability::with('pharmacy')
            ->where('medicine_id', $medicine_id)
            ->get();

        return response()->json($availability);
    }
}
