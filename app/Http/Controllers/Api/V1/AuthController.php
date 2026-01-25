<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\ForgotPassword;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'sometimes|string|max:120',
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
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada exitosamente'
        ], 200);
    }

    public function register(Request $request)
    {
        // 1) Validación mínima para poder buscar
        $baseValidator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'device_name' => ['sometimes', 'string', 'max:120'],
        ]);

        if ($baseValidator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validación fallida',
                'errors' => $baseValidator->errors(),
            ], 422);
        }

        $email = mb_strtolower(trim($request->input('email')));

        // 2) Si ya existe, devolvemos el usuario (SIN TOKEN)
        $existing = User::where('email', $email)->first();
        if ($existing) {
            return response()->json([
                'success' => true,
                'message' => 'El usuario ya estaba creado',
                'user' => $existing,
                'exists' => true,
            ], 200);
        }

        // 3) Si NO existe, ahora sí validamos lo necesario para crearlo
        $createValidator = Validator::make($request->all(), [
            // 'username' => ['required', 'string', 'min:2', 'max:255'],
            'firstname' => ['required', 'string', 'min:2', 'max:255'],
            'lastname' => ['required', 'string', 'min:2', 'max:255'],
            'password' => ['required', 'string', 'min:5', 'max:255', 'confirmed'],
            // Unique aquí (aunque ya revisamos arriba) para cubrir carreras + consistencia
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        ]);

        if ($createValidator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validación fallida',
                'errors' => $createValidator->errors(),
            ], 422);
        }

        try {

            $firstname = mb_strtolower(trim($request->input('firstname')));
            $lastname = mb_strtolower(trim($request->input('lastname')));
            $username = str_replace(' ', '_', $firstname . $lastname);

            $user = User::create([
                'username' => $username,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'password' => Hash::make($request->input('password')),
            ]);
        } catch (QueryException $e) {
            // Por si entran dos requests al tiempo: ya lo creó el otro
            $user = User::where('email', $email)->first();

            return response()->json([
                'success' => true,
                'message' => 'El usuario ya estaba creado',
                'user' => $user,
                'exists' => true,
            ], 200);
        }

        $tokenName = $request->input('device_name', 'authToken');
        $token = $user->createToken($tokenName)->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Usuario registrado exitosamente',
            'user' => $user,
            'token' => $token,
            'exists' => false,
        ], 200);
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
