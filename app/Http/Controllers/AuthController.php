<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Step 1: Validate the request
        $request->validate([
            'email' => 'required|email', // Ensure the email is valid
            'password' => 'required',   // Ensure the password is provided
        ]);
    
        // Step 2: Retrieve the user by email
        $user = User::with('role')->where('email', $request->email)->first();    
        // Step 3: Verify the password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials. Please check your email and password.',
            ], 401); // Return a 401 Unauthorized status code
        }
    
        // Step 4: Generate a token (optional, if using Sanctum)
        $token = $user->createToken('auth_token')->plainTextToken;
    
        // Step 5: Return a successful response
        return response()->json([
            'message' => 'Login successful.',
            'user' => $user,
            'token' => $token, // Include the token in the response
        ], 200); // Return a 200 OK status code
    }

    public function logout()
    {
        // Logout logic here
        return response()->json(['message' => 'Logged out successfully']);
    }
}
