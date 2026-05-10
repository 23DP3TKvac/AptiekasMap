<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\Medicine;
use App\Models\Pharmacy;
use App\Models\SearchHistory;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        // Zāļu skaits katrā aptiekā
        $medicinesPerPharmacy = Availability::with('pharmacy')
            ->where('status', 'available')
            ->get()
            ->groupBy('pharmacy_id')
            ->map(fn($items) => [
                'pharmacy' => $items->first()->pharmacy->name ?? 'Nezināma',
                'count'    => $items->count(),
            ])->values();

        // Vidējā cena katrai zālei
        $avgPricePerMedicine = Availability::with('medicine')
            ->where('status', 'available')
            ->get()
            ->groupBy('medicine_id')
            ->map(fn($items) => [
                'medicine' => $items->first()->medicine->name ?? 'Nezināma',
                'avgPrice' => round($items->avg('price'), 2),
                'minPrice' => round($items->min('price'), 2),
                'maxPrice' => round($items->max('price'), 2),
            ])->values();

        // Populārākie meklējumi
        $topSearches = DB::table('search_history')
            ->select('medicine_name', DB::raw('count(*) as count'))
            ->groupBy('medicine_name')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Kopsavilkums
        $summary = [
            'totalMedicines'  => Medicine::count(),
            'totalPharmacies' => Pharmacy::count(),
            'totalAvailable'  => Availability::where('status', 'available')->count(),
            'totalSearches'   => SearchHistory::count(),
        ];

        return response()->json([
            'medicinesPerPharmacy' => $medicinesPerPharmacy,
            'avgPricePerMedicine'  => $avgPricePerMedicine,
            'topSearches'          => $topSearches,
            'summary'              => $summary,
        ]);
    }
}
