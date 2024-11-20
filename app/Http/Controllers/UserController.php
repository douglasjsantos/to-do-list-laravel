<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserControler extends Controller
{
    public function index()
    {
        return response()->json(User::all(), 200);
    }


     public function show($id)
     {
         $user = User::find($id);
         if (!$user) {
             return response()->json(['message' => 'User not found'], 404);
         }
         return response()->json($user, 200);
     }


     public function store(Request $request)
     {
         $validated = $request->validate([
             'name' => 'required|string|max:255',
             'email' => 'required|email|unique:users,email',
             'password' => 'required|string|min:6',
             'role' => 'in:user,admin',
         ]);

         $validated['password'] = Hash::make($validated['password']);
         $user = User::create($validated);

         return response()->json($user, 201);
     }


     public function update(Request $request, $id)
     {
         $user = User::find($id);
         if (!$user) {
             return response()->json(['message' => 'User not found'], 404);
         }

         $validated = $request->validate([
             'name' => 'sometimes|string|max:255',
             'email' => 'sometimes|email|unique:users,email,' . $id,
             'password' => 'sometimes|string|min:6',
             'role' => 'sometimes|in:user,admin',
         ]);

         if (isset($validated['password'])) {
             $validated['password'] = Hash::make($validated['password']);
         }

         $user->update($validated);
         return response()->json($user, 200);
     }


     public function destroy($id)
     {
         $user = User::find($id);
         if (!$user) {
             return response()->json(['message' => 'User not found'], 404);
         }

         $user->delete();
         return response()->json(['message' => 'User deleted successfully'], 200);
     }
}
