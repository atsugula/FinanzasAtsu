<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ForgotPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
	use Notifiable;

    public function routeNotificationForMail() {
        return request()->email;
    }
	
    public function login(Request $request)
    {
        // Validación
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Intentar login
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        // Obtener usuario autenticado
        $user = Auth::user();

        // Crear token de acceso
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function logout(Request $request)
    {
        // Revocar el token
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Sesión cerrada exitosamente'], 401);
    }
	
	public function register(Request $request) {

        // Validación
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:255|min:2',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:5|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create($request->only('username', 'email', 'password'));
        auth()->login($user);
		
		$token = $user->createToken('authToken')->plainTextToken;
		
		return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'user' => $user,
            'token' => $token,
        ], 200);

	}

    public function sendPasswordResetLink(Request $request)
    {

        // Validación
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $this->notify(new ForgotPassword($user->id));

            return response()->json([
                'message' => 'Correo enviado correctamente.',
                'user' => $user,
            ], 200);
        }

        return response()->json(['message' => 'Usuario no encontrado.'], 404);
    }

}
