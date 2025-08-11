<?php

namespace App\Http\Controllers;

use App\Models\Request;
use App\Models\ItemDetail;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserRequestController extends Controller
{
    public function store(HttpRequest $request)
    {
        $request->validate([
            'item_detail_id' => 'required|exists:item_details,id',
            'data_inizio' => 'required|date',
            'data_fine' => 'required|date|after_or_equal:data_inizio',
            'note' => 'nullable|string',
        ]);
        $itemDetail = ItemDetail::findOrFail($request->item_detail_id);
        if ($itemDetail->stato !== 'disponibile') {
            return back()->withErrors(['item_detail_id' => 'Il pezzo non è disponibile.']);
        }
        $req = Request::create([
            'user_id' => Auth::id(),
            'item_detail_id' => $request->item_detail_id,
            'data_inizio' => $request->data_inizio,
            'data_fine' => $request->data_fine,
            'note' => $request->note,
            'stato' => 'in_attesa',
        ]);
    // cambia stato item_detail
    $itemDetail->update(['stato' => 'in_attesa']);
        return redirect()->route('user.requests.index')->with('success', 'Richiesta inviata!');
    }

    public function index()
    {
        $requests = Request::with(['itemDetail.item', 'itemDetail'])->where('user_id', Auth::id())->latest()->get();
        return Inertia::render('User/Requests', [
            'requests' => $requests,
        ]);
    }
}
