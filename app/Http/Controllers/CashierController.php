<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cashier;

class CashierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cashiers = Cashier::all();
        return response()->json($cashiers); // Return all cashiers as JSON
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_no' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'store_id' => 'required|exists:store,store_id',
        ]);

        $cashier = Cashier::create($validatedData);

        return response()->json(['message' => 'Cashier created successfully', 'data' => $cashier], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cashier = Cashier::findOrFail($id);
        return response()->json($cashier);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cashier = Cashier::findOrFail($id);

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_no' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'store_id' => 'required|exists:store,store_id',
        ]);

        $cashier->update($validatedData);

        return response()->json(['message' => 'Cashier updated successfully', 'data' => $cashier]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cashier = Cashier::findOrFail($id);
        $cashier->delete();

        return response()->json(['message' => 'Cashier deleted successfully']);
    }
}
