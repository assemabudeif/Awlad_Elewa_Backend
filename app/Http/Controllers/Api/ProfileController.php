<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json([
            'message' => 'Profile fetched successfully',
            'user' => $request->user(),
        ], 200);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255|unique:users,email,' . $user->id,
            'phone1' => 'sometimes|required|string|max:20',
            'phone2' => 'nullable|string|max:20',
            'password' => 'nullable|min:6',
        ]);
        if (isset($data['password']) && $data['password'] != null) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $user->update($data);
        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
        ], 200);
    }

    public function destroy(Request $request)
    {
        $user = $request->user();
        $user->delete();
        return response()->json([
            'message' => 'Profile deleted successfully',
        ], 200);
    }
}
