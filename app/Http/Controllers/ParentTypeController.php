<?php

namespace App\Http\Controllers;

use App\Models\ParentType;
use Illuminate\Http\Request;

class ParentTypeController extends Controller
{
       public function index()
    {
        return ParentType::all();
    }
     /**
     * Store a newly created brand in storage.
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'store_id' => 'required|exists:store,store_id',
        ]);

        // Create a new brand record
        $parent_type = ParentType::create([
            'name' => $request->name,
            'store_id' => $request->store_id,
        ]);

        // Return a response (could be a redirect or a JSON response)
        return response()->json([
            'message' => 'Parent type created successfully',
            'parent_type' => $parent_type,
        ], 201);
    }
     public function showByCategory($storeId)
    {
        // Fetch products that belong to the given store ID
        $parent_type = ParentType::where('store_id', $storeId)->get();

        // Check if products exist for the store
        if ($parent_type->isEmpty()) {
            return response()->json([
                'message' => 'No Category found for this store.'
            ], 404);
        }

        return response()->json($parent_type);
    }

     /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $parent_type = ParentType::findOrFail($id);
 
        $parent_type->delete();

        return $parent_type;
    }

}
