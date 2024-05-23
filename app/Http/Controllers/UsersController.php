<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Import the User model
use Inertia\Inertia;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all(); // Fetch all users

        return response()->json(['users' => $users]);
    }

    public function create()
    {
        return Inertia::render('UsersCreate'); // Render the UsersCreate page
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email', // Make sure email is unique
        'password' => 'required|string|min:8', // Add password validation rules
    ]);

    try {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password); // Encrypt the password
        $user->save();

        return redirect('/users')->with('success', 'User created successfully');
    } catch (\Exception $e) {
        return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
    }
}

    public function edit($id)
    {
        $user = User::findOrFail($id); // Find the user by ID

        return Inertia::render('UsersEdit', ['user' => $user]); // Render the UsersEdit page with user data
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id, // Make sure email is unique except for the current user
            'password' => 'nullable|string|min:8', // Password can be nullable for updates
        ]);

        $user = User::findOrFail($id); // Find the user by ID
        $user->update($request->all()); // Update the user with new data

        return redirect('/users')->with('success', 'User updated successfully'); // Redirect with success message
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id); // Find the user by ID
        $user->delete(); // Delete the user

        return response()->json(['message' => 'User deleted successfully']);
    }
}
