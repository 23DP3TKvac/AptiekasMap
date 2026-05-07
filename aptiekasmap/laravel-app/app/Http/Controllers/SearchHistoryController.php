<?php

namespace App\Http\Controllers;

use App\Models\SearchHistory;
use Illuminate\Http\Request;

class SearchHistoryController extends Controller
{
    public function index(Request $request)
    {
        $history = SearchHistory::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
        return response()->json($history);
    }

    public function store(Request $request)
    {
        $request->validate(['medicine_name' => 'required|string|max:100']);

        $entry = SearchHistory::create([
            'user_id'       => $request->user()->id,
            'medicine_name' => $request->medicine_name,
        ]);
        return response()->json($entry, 201);
    }
}
