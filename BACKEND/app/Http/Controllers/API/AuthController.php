<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Исправлено: используем правильный namespace
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Получаем входные данные
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        
        $usernameInput = $request->input('username');
        $password = $request->input('password');
        
        // Ищем пользователя по email или имени
        $user = User::where('email', $usernameInput)
                    ->orWhere('name', $usernameInput)
                    ->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json(['message' => 'invalid login'], 401);
        }

        // Генерируем токен (используем исходное значение username)
        $token = sha1($usernameInput . time());

        // Сохраняем токен в кэше (на 1 час)
        Cache::put('auth_token_' . $token, $user->id, now()->addHour());

        return response()->json([
            'token' => $token,
            'role'  => $user->role,
        ], 200);
    }

    public function logout(Request $request)
    {
        $authHeader = $request->header('Authorization');
        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return response()->json(['message' => 'Unauthorized user'], 401);
        }

        $token = $matches[1];

        // Удаляем токен из кэша (ключ должен совпадать с тем, что использовался при сохранении)
        Cache::forget('auth_token_' . $token);

        return response()->json(['message' => 'logout success'], 200);
    }
}
