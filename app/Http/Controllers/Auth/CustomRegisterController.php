<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register'); // Ваш шаблон регистрации
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $isFirstUser = User::count() === 0;

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'patronymic' => $request->patronymic,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $isFirstUser ? 'admin' : 'user',
            'status' => $isFirstUser ? 'active' : 'inactive',
            'uuid' => Str::uuid()->toString(),
        ]);

        event(new Registered($user));

        auth()->login($user);

        return redirect()->route('dashboard'); // Или другой маршрут после регистрации
    }
}
