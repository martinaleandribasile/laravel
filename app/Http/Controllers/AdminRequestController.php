<?php

namespace App\Http\Controllers;

use App\Models\Request;
use App\Models\ItemDetail;
use Illuminate\Http\Request as HttpRequest;
use Inertia\Inertia;

class AdminRequestController extends Controller
{
    // Mostra tutte le richieste di inventario e acquisto, con eventuale filtro per stato
    public function index(HttpRequest $request)
    {
        $inventarioQuery = Request::with(['user', 'itemDetail.item'])
            ->where('tipo', 'inventario');
        $acquistoQuery = Request::with(['user', 'category'])
            ->where('tipo', 'acquisto');
        if ($request->filled('stato')) {
            $inventarioQuery->where('stato', $request->stato);
            $acquistoQuery->where('stato', $request->stato);
        }
        $requests_inventario = $inventarioQuery->latest()->get();
        $requests_acquisto = $acquistoQuery->latest()->get();
        return Inertia::render('Admin/Requests', [
            'requests_inventario' => $requests_inventario,
            'requests_acquisto' => $requests_acquisto,
            'filters' => $request->only(['stato'])
        ]);
    }

    // Conferma una richiesta (acquisto o inventario) e aggiorna lo stato
    public function confirm(\App\Models\Request $user_request)
    {
        if ($user_request->stato !== 'in_attesa') {
            return back()->withErrors(['msg' => 'Richiesta già gestita.']);
        }
        if ($user_request->tipo === 'acquisto') {
            // Crea nuovo item e dettaglio pezzo
            $item = \App\Models\Item::create([
                'name' => $user_request->nome_articolo,
                'description' => 'Creato da richiesta acquisto',
                'category_id' => $user_request->category_id,
            ]);
            $itemDetail = \App\Models\ItemDetail::create([
                'item_id' => $item->id,
                'seriale' => 'AUTO-' . strtoupper(uniqid()),
                'colore' => 'nero',
                'ram' => null,
                'altro' => null,
                'stato' => 'in_uso',
                'data_inizio_uso' => now()->toDateString(),
                'data_fine_uso' => null,
            ]);
            $user_request->item_detail_id = $itemDetail->id;
            $user_request->update(['stato' => 'confermata', 'item_detail_id' => $itemDetail->id]);
        } else {
            $user_request->update(['stato' => 'confermata']);
            if ($user_request->itemDetail) {
                $user_request->itemDetail->update(['stato' => 'in_uso']);
            }
        }
        return back()->with('success', 'Richiesta confermata!');
    }

    // Annulla una richiesta e aggiorna lo stato
    public function cancel(\App\Models\Request $user_request)
    {
        if ($user_request->stato !== 'in_attesa') {
            return back()->withErrors(['msg' => 'Richiesta già gestita.']);
        }
        $user_request->update(['stato' => 'annullata']);
        if ($user_request->tipo === 'inventario' && $user_request->itemDetail) {
            $user_request->itemDetail->update(['stato' => 'disponibile']);
        }
        return back()->with('success', 'Richiesta annullata!');
    }
}
