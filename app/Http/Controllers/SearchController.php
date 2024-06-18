<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SearchQuery as SearchQuery;

class SearchController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'searchquery' => 'required|string',
        ]);

        //$searchQuery = new SearchQuery();
        //$searchQuery->query = $request->query;
        //$searchQuery->filters = $this->parseFilters($request->filters);
        //$searchQuery->valid_until = now()->addHour(5);
        //$searchQuery->results_count = $request->results_count;
        //$searchQuery->status = "new";
        //$searchQuery->save();

        $searchQuery = SearchQuery::create([
            'query' => $request->searchquery,
            'filters' => $this->parseFilters($request->filters),
            'valid_until' => now()->addHour(5),
            'results_count' => $request->results_count,
            'status' => "new",
        ]);

        return redirect()->route('results');
    }

    public function parseFilters($filters)
    {
        return json_decode($filters, true);
    }
}
