<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Mostra la lista di tutte le categorie
    public function index()
    {
        $categories = Category::withCount('items')->orderBy('name')->get();
        return Inertia::render('Admin/Categories/Index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    // Mostra il form per creare una nuova categoria
    public function create()
    {
        return Inertia::render('Admin/Categories/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    // Salva una nuova categoria nel database
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);
        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success', 'Categoria creata!');
    }

    /**
     * Display the specified resource.
     */
    // Mostra i dettagli di una categoria specifica
    public function show(Category $category)
    {
        $category->load('items');
        return Inertia::render('Admin/Categories/Show', [
            'category' => $category,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // Mostra il form per modificare una categoria
    public function edit(Category $category)
    {
        return Inertia::render('Admin/Categories/Edit', [
            'category' => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    // Aggiorna i dati di una categoria esistente
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);
        $category->update($data);
        return redirect()->route('admin.categories.index')->with('success', 'Categoria aggiornata!');
    }

    /**
     * Remove the specified resource from storage.
     */
    // Elimina una categoria dal database
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Categoria eliminata!');
    }
}
