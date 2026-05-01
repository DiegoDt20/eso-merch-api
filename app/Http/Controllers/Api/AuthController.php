<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Registra un nuevo usuario.
     * Recibe: name, email, password
     * Retorna: datos del usuario + token de acceso
     */
    public function register(Request $request)
    {
        // Valida los datos recibidos
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        // Crea el usuario en la base de datos
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Genera un token de acceso para el usuario
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Inicia sesión con email y contraseña.
     * Retorna: datos del usuario + token de acceso
     */
    public function login(Request $request)
    {
        // Valida los datos recibidos
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Busca el usuario por email
        $user = User::where('email', $request->email)->first();

        // Verifica que el usuario existe y la contraseña es correcta
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales no son correctas.'],
            ]);
        }

        // Genera un nuevo token de acceso
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ]);
    }

    /**
     * Cierra la sesión del usuario autenticado.
     * Elimina el token actual de acceso.
     */
    public function logout(Request $request)
    {
        // Elimina el token actual
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente.',
        ]);
    }

    /**
     * Retorna los datos del usuario autenticado.
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}