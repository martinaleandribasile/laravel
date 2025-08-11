<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::with(['category', 'dettaglioPezzi'])->orderBy('created_at', 'desc')->get();
        $items = $items->map(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'category' => $item->category,
                'quantity' => $item->dettaglioPezzi->count(),
                'disponibili' => $item->disponibili,
                'in_uso' => $item->in_uso,
                'in_attesa' => $item->in_attesa,
                'dettaglio_pezzi' => $item->dettaglioPezzi,
            ];
        });
        return Inertia::render('Admin/Items/Index', [
            'items' => $items,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return Inertia::render('Admin/Items/Create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:0',
        ]);
        Item::create($data);
        return redirect()->route('admin.items.index')->with('success', 'Articolo creato con successo!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        $item->load(['category', 'dettaglioPezzi']);
        return Inertia::render('Admin/Items/Show', [
            'item' => $item,
            'dettaglio_pezzi' => $item->dettaglioPezzi,
            'disponibili' => $item->disponibili,
            'in_uso' => $item->in_uso,
            'in_attesa' => $item->in_attesa,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
         $categories = Category::all();
        return Inertia::render('Admin/Items/Edit', [
            'item' => $item,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:0',
        ]);
        $item->update($data);
        return redirect()->route('admin.items.index')->with('success', 'Articolo aggiornato!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('admin.items.index')->with('success', 'Articolo eliminato!');
    }
}
