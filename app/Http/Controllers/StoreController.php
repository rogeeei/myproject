<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Http\Requests\StoreRequest;
use Illuminate\Support\Facades\Log;

class StoreController extends Controller
{
       public function index()
    {
        return Store::all();
    }
/**
 * Store a newly created resource in storage.
 */
public function store(StoreRequest $request)
{
    $validated = $request->validated();

    // Default user_id if not provided
    if (!isset($validated['user_id'])) {
        $validated['user_id'] = 1; // Replace 1 with your default user ID
    }

    // Handle image upload
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        if ($image->isValid()) {
            $imagePath = $image->store('images', 'public');
            $validated['image_path'] = $imagePath;

            Log::info('Image stored successfully: ' . $imagePath);
        } else {
            Log::error('Uploaded image is not valid.');
        }
    } else {
        Log::error('No image file was uploaded.');
    }

    // Test: Manually set the image_path for debugging
    // $validated['image_path'] = 'images/test-image.jpg';  // Test manually setting image path

    // Check if image_path is set before creating the store
    if (isset($validated['image_path'])) {
        Log::info('Image path: ' . $validated['image_path']);
    } else {
        Log::error('No image path set in validated data.');
    }

    // Create the store with the validated data
    $store = Store::create($validated);

    return response()->json($store, 201);
}




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Store::findOrFail($id);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(StoreRequest $request, string $id)
    {
        $validated = $request->validated();
        
        $store = Store ::findOrFail($id);

        $store ->update($validated);

        return $store;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $store = Store::findOrFail($id);
 
        $store->delete();

        return $store;
    }
}
