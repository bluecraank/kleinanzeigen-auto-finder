<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScrapeController;

Route::get('/', function () {
    $anzeigen = [];
    $query = '';
    return view('welcome', compact('anzeigen', 'query'));
});

Route::get('/search', [ScrapeController::class, 'search'])->name('search');

Route::get('/scrape/next', function(Request $request) {
    $query = $request->input('query');
    $page = $request->input('currentPage');

    // dd($query, $page);

    $anzeigen = (new ScrapeController)->browseUrl($query, $page);
    return response()->json($anzeigen);
});

Route::get('/scrape/details/{url}', [ScrapeController::class, 'browseDetails'])->name('details');
