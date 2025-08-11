<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Request as ItemRequest;
use App\Models\Item;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
    $year = 2025;


        // Array dei mesi dell'anno
        $months = collect(range(1, 12))->map(function($m) use ($year) {
            return sprintf('%04d-%02d', $year, $m);
        });

        // Top 5 articoli più richiesti per ogni mese
        $topItemsYear = $months->mapWithKeys(function($month) use ($year) {
            $items = ItemRequest::select('item_detail_id', DB::raw('count(*) as total'))
                ->whereNotNull('item_detail_id')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', substr($month, 5, 2))
                ->groupBy('item_detail_id')
                ->orderByDesc('total')
                ->with('itemDetail.item')
                ->take(5)
                ->get();
            return [$month => $items];
        });

        // Totale richieste per mese
        $monthlyRequestsYear = $months->mapWithKeys(function($month) use ($year) {
            $total = ItemRequest::whereYear('created_at', $year)
                ->whereMonth('created_at', substr($month, 5, 2))
                ->count();
            return [$month => $total];
        });

        // Richieste per categoria per mese
        $categoryMonthlyYear = $months->mapWithKeys(function($month) use ($year) {
            $cat = ItemRequest::select('category_id', DB::raw('count(*) as total'))
                ->whereNotNull('category_id')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', substr($month, 5, 2))
                ->groupBy('category_id')
                ->get();
            return [$month => $cat];
        });

        $categories = Category::all();

        return Inertia::render('Admin/Statistics', [
            'topItemsYear' => $topItemsYear,
            'monthlyRequestsYear' => $monthlyRequestsYear,
            'categoryMonthlyYear' => $categoryMonthlyYear,
            'categories' => $categories,
        ]);
    }
}
