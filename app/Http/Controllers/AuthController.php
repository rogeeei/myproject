<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth; // Add this for authentication

class AuthController extends Controller
{
   public function login(Request $request)
{
    // Validate incoming request data
    $validated = $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    // Find the user by username
    $user = User::where('username', $validated['username'])->first();

    // Check if the user exists and the password is correct
    if (!$user || !Hash::check($validated['password'], $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    // Generate the token for the authenticated user
    $token = $user->createToken('User Token')->plainTextToken; // This should work with the correct relationships

    // Prepare the response
    $response = [
        'token' => $token,
        'data' => [
            'role' => $user->role, // Ensure 'role' exists in your User model and table
            'user_id' => $user->user_id,
        ],
    ];

    // Return the successful login response
    return response()->json(['message' => 'Login successful', 'data' => $response], 200);
}

    public function logout(Request $request)
    {
        // Revoke the token for the authenticated user
        $request->user()->currentAccessToken()->delete();

        // Return a success response
        return response()->json(['message' => 'Logout successful'], 200);
    }

  public function vendorLogin(Request $request)
    {
        // Validate incoming request data
        $validated = $request->validate([
            'email' => 'required|email|string', // Ensure the email is validated correctly
            'password' => 'required|string',
        ]);

        // Find the user by email (fixing the incorrect 'username' field)
        $vendor = Vendor::where('email', $validated['email'])->first(); // Searching by 'email'

        // Check if the user exists and the password is correct
        if (!$vendor || !Hash::check($validated['password'], $vendor->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Generate the token for the authenticated user
        $token = $vendor->createToken('User Token')->plainTextToken;

        // Prepare the response with the necessary user data
        $response = [
            'token' => $token,
            'data' => [
                
                'vendor_id' => $vendor->vendor_id, // Ensure 'user_id' is a valid attribute
            ],
        ];

        // Return the successful login response
        return response()->json(['message' => 'Login successful', 'data' => $response], 200);
    }
}
