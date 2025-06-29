<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = \App\Models\Payment::with(['trip.tripRequest.passenger.user', 'trip.driver.user'])->get();
        return view('payments', compact('payments'));
    }

    public function show($id)
    {
        $payment = Payment::with(['trip'])->findOrFail($id);
        return response()->json($payment);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'amount' => 'required|numeric',
            'method' => 'required|string|max:50',
            'paid_at' => 'required|date',
        ]);
        $payment = \App\Models\Payment::create($validated);
        return redirect('/')->with('success', 'Payment added successfully!');
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $validated = $request->validate([
            'amount' => 'sometimes|required|numeric',
            'method' => 'sometimes|required|string|max:50',
            'paid_at' => 'sometimes|required|date',
        ]);
        $payment->update($validated);
        return response()->json($payment);
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return response()->json(['message' => 'Payment deleted successfully']);
    }

    public function create()
    {
        $trips = \App\Models\Trip::with(['tripRequest.passenger.user', 'driver.user'])->get();
        return view('add_payment', compact('trips'));
    }
}
