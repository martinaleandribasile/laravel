<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemDetail;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ItemDetailController extends Controller
{
    // Restituisce lo storico degli utilizzi di un dettaglio pezzo (item_detail)
    public function storico($id)
    {
        $detail = ItemDetail::with(['usages.user'])->findOrFail($id);
        return response()->json([
            'storico' => $detail->usages
        ]);
    }
    // Salva un nuovo dettaglio pezzo per un articolo
    public function store(Request $request, Item $item)
    {
        $data = $request->validate([
            'seriale' => 'required|string|unique:item_details,seriale',
            'colore' => 'nullable|string',
            'ram' => 'nullable|string',
            'altro' => 'nullable|string',
            'stato' => 'required|in:disponibile,in_uso,in_attesa',
            'data_inizio_uso' => 'nullable|date',
            'data_fine_uso' => 'nullable|date',
        ]);
        $data['item_id'] = $item->id;
        ItemDetail::create($data);
        return redirect()->route('admin.items.show', $item->id)->with('success', 'Pezzo aggiunto con successo!');
    }
}
