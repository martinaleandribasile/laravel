<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserInventoryController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'user') {
            abort(403);
        }
        $query = Item::with(['category', 'dettaglioPezzi' => function($q) {
            $q->where('stato', 'disponibile');
        }]);
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        $items = $query->get();
        $categories = Category::all();
        return Inertia::render('User/Inventory', [
            'items' => $items,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category_id']),
        ]);
    }
    public function show(Item $item)
    {
        if (Auth::user()->role !== 'user') {
            abort(403);
        }
        $item->load(['category', 'dettaglioPezzi']);
        return Inertia::render('User/ItemShow', [
            'item' => $item,
        ]);
    }
}
