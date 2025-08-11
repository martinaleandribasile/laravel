<?php

namespace App\Http\Controllers;

use App\Models\Request;
use App\Models\ItemDetail;
use Illuminate\Http\Request as HttpRequest;
use Inertia\Inertia;

class AdminRequestController extends Controller
{
    public function index(HttpRequest $request)
    {
        $query = Request::with(['user', 'itemDetail.item']);
        if ($request->filled('stato')) {
            $query->where('stato', $request->stato);
        }
        $requests = $query->latest()->get();
        return Inertia::render('Admin/Requests', [
            'requests' => $requests,
            'filters' => $request->only(['stato'])
        ]);
    }

    public function confirm(\App\Models\Request $user_request)
    {
        if ($user_request->stato !== 'in_attesa') {
            return back()->withErrors(['msg' => 'Richiesta già gestita.']);
        }
        $user_request->update(['stato' => 'confermata']);
        $user_request->itemDetail->update(['stato' => 'in_uso']);
        return back()->with('success', 'Richiesta confermata!');
    }

    public function cancel(\App\Models\Request $user_request)
    {
        if ($user_request->stato !== 'in_attesa') {
            return back()->withErrors(['msg' => 'Richiesta già gestita.']);
        }
        $user_request->update(['stato' => 'annullata']);
        $user_request->itemDetail->update(['stato' => 'disponibile']);
        return back()->with('success', 'Richiesta annullata!');
    }
}
