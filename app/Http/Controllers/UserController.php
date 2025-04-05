<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Position;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class UserController extends Controller
{

    public function index()
{
    // Fetch all users with their roles and positions
    $users = User::with(['role', 'position'])
    ->whereHas('role', function ($query) {
        $query->where('name', '!=', 'Admin'); // Exclude users with the role "Admin"
    })
    ->get();
    // Return the users as JSON
    return response()->json($users);
}
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:HR,User',
            'position' => 'required|in:Manager,Officer',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // Handle the uploaded file
        $photo = $request->file('photo');
        $photoBlob = base64_encode(file_get_contents($photo)); // Convert file to BLOB
    
        // Create the user
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make('default_password'), // Set a default password
            'photo' => $photoBlob, // Store the BLOB in the database
        ]);
    
        // Assign role and position (assuming relationships exist)
        $role = Role::where('name', $request->role)->first();
        $position = Position::create([
            'name' => $request->position
        ]);
        $position->save();
    
        $user->role()->associate($role);
        $user->position()->associate($position);
        $user->save();
    
        return response()->json(['message' => 'User created successfully'], 201);
    }

    public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $validatedData = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
    ]);

    $user->update($validatedData);

    return response()->json(['message' => 'User updated successfully'], 200);
}

public function destroy($id)
{
    $user = User::findOrFail($id);
    $user->delete();

    return response()->json(['message' => 'User deleted successfully'], 200);
}
public function show($id)
{
    // Find the user by ID and include their role and position
    $user = User::with(['role', 'position'])->findOrFail($id);

    return response()->json($user);
}
public function changePassword(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'newPassword' => 'required|min:6',
    ]);

    // Find the user by email
    $user = User::where('email', $request->email)->firstOrFail();

    // Update the password
    $user->password = Hash::make($request->newPassword);
    $user->save();

    return response()->json(['message' => 'Password updated successfully'], 200);
}

}
