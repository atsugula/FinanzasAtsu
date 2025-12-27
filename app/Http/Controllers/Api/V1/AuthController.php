<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\ForgotPassword;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'sometimes|string|max:120', // opcional, útil para móvil/web
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        /** @var User $user */
        $user = Auth::user();

        // (Opcional) si quieres 1 token por dispositivo:
        // $deviceName = $request->input('device_name', 'authToken');
        // $user->tokens()->where('name', $deviceName)->delete();

        $tokenName = $request->input('device_name', 'authToken');
        $token = $user->createToken($tokenName)->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function logout(Request $request)
    {
        // Revoca SOLO el token actual (recomendado)
        $request->user()->currentAccessToken()?->delete();

        // Si quieres cerrar sesión en todos los dispositivos:
        // $request->user()->tokens()->delete();

        return response()->json(['message' => 'Sesión cerrada exitosamente'], 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:255|min:2',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:5|max:255|confirmed', // agrega password_confirmation
            'device_name' => 'sometimes|string|max:120',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'username' => $request->username,
            'email' => mb_strtolower($request->email),
            'password' => Hash::make($request->password), // clave
        ]);

        // No necesitas auth()->login($user) para emitir token,
        // pero no hace daño si tu app lo usa.
        // auth()->login($user);

        $tokenName = $request->input('device_name', 'authToken');
        $token = $user->createToken($tokenName)->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function sendPasswordResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', mb_strtolower($request->email))->first();

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado.'], 404);
        }

        // Notifica al usuario (lo correcto)
        $user->notify(new ForgotPassword($user->id));

        return response()->json([
            'message' => 'Correo enviado correctamente.',
        ], 200);
    }
}
