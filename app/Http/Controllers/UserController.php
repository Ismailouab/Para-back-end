<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // for converting the data to json format
    public function index(){

        return User::with('role')->get();  // Return user with their roles
    }
    // for showing the data using id
    public function show($id){

        return User::with('role')->findOrFail($id);
    }
    // for storing the data
    public function store(Request $request){

        $user = User::create($request->all());
        return response()->json($user, 201);
    }
    // for updating the data
    public function update(Request $request, $id){

        $user = User::findOrFail($id);
        $user->update($request->all());
        return response()->json($user, 200);
    }
    // for deleting the data
    public function destroy($id){

        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(null, 204);
    }
}
