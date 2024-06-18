<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScrapeController;
use App\Models\SearchQuery;
use App\Http\Controllers\SearchController;

Route::get('/', function () {
    return view('start');
});

Route::post('/search', [SearchController::class, 'store'])->name('search');

Route::get('/search/{id}', [SearchQuery::class])->name('results');

Route::get('/scrape/next', function(Request $request) {
    $query = $request->input('query');
    $page = $request->input('currentPage');

    // dd($query, $page);

    $anzeigen = (new ScrapeController)->browseUrl($query, $page);
    return response()->json($anzeigen);
});

Route::get('/scrape/details/{url}', [ScrapeController::class, 'browseDetails'])->name('details');
