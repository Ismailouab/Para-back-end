<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reclamation;
use Illuminate\Support\Facades\Auth;

class ReclamationController extends Controller
{
    // Get all reclamations
    public function index()
    {
        $reclamations = Reclamation::all();
        return response()->json($reclamations);
    }

    // Show a specific reclamation by ID
    public function show($id)
    {
        $reclamation = Reclamation::find($id);
        if ($reclamation) {
            return response()->json($reclamation);
        }
        return response()->json(['message' => 'Reclamation not found'], 404);
    }

    // Store a new reclamation
    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'order_id' => 'required|exists:orders,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'nullable|in:pending,in_progress,resolved',
        ]);

        $reclamation = Reclamation::create($validated);
        return response()->json($reclamation, 201);
    }

    // Update a reclamation
    public function update(Request $request, Reclamation $reclamation)
    {
        $validated = $request->validate([
            'description' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:pending,in_progress,resolved',
        ]);

        $reclamation->update($validated);
        return response()->json($reclamation, 200);
    }

    // Delete a reclamation
    public function destroy(Reclamation $reclamation)
    {
        $reclamation->delete();
        return response()->json(['message' => 'Reclamation deleted successfully'], 200);
    }
}
