<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Role;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        // Check if the user exists and the credentials are correct
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone'=>$user->phone,
                'address'=>$user->address,
                'role_id' => $user->role_id,
            ]
        ], 200);
    }
    public function register(Request $request)
    {
        try {
            // Validate the incoming request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'required|string|max:15', // Adjust as needed
                'address' => 'required|string|max:255',
            ]);

            // Fetch the 'client' role by name
            $role = Role::where('name', 'client')->first();

            // Check if the role exists, if not return an error
            if (!$role) {
                return response()->json(['error' => 'Role not found'], 400);
            }

            // Create the user and catch any errors related to database or validation
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone, // Takes phone from request (null if not provided)
                'address' => $request->address, // Takes address from request (null if not provided)
            ]);
            // Assign the 'client' role to the user
            $user->role()->associate($role);
            $user->save();

            // Generate a token for the user
            $token = $user->createToken('auth_token')->plainTextToken;

            // Return the token as a response
            return response()->json(['token' => $token], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors (if validation fails)
            return response()->json(['error' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            // Handle any other errors (e.g., database issues)
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request...
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Token deleted'], 200);
    }
}
