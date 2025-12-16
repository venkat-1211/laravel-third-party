<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = $request->input('q');

        // Simple Scout search:
        $results = Product::search($q)
            ->take(20)
            ->get(); // returns Eloquent models with score

        return view('search.results', compact('results','q'));
    }
}
