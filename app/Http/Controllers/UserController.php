<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Solo para administradores (añade middleware auth y roles en rutas)

    public function index()
    {
        return response()->json(User::all());
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'Usuario no encontrado'], 404);

        return response()->json($user);
    }

   public function update(Request $request, $id)
{
    $user = User::find($id);
    if (!$user) return response()->json(['message' => 'Usuario no encontrado'], 404);

    $request->validate([
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|max:255|unique:users,email,' . $id,
        'password' => 'nullable|string|min:6',
        'is_admin' => 'sometimes|boolean',
    ]);

    if ($request->has('name')) $user->name = $request->name;
    if ($request->has('email')) $user->email = $request->email;
    if ($request->filled('password')) $user->password = Hash::make($request->password);
    if ($request->has('is_admin')) $user->is_admin = $request->is_admin; // Añadido

    $user->save();

    return response()->json($user);
}


    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'Usuario no encontrado'], 404);

        $user->delete();

        return response()->json(['message' => 'Usuario eliminado']);
    }
    public function updateOwnProfile(Request $request)
{
    $user = auth()->user();

 $request->validate([
    'name' => 'sometimes|nullable|string|max:255',
    'password' => 'nullable|string|min:6',
]);


   if ($request->filled('name')) {
    $user->name = $request->name;
}

if ($request->filled('password')) {
    $user->password = Hash::make($request->password);
}


    $user->save();

    return response()->json($user);
}


}
