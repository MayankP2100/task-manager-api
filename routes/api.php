<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'message' => 'Health is up',
    ]);
});

Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
    ]);

    $user = User::create($validated);

    Auth::login($user);
    $request->session()->regenerate();

    return response()->json([
        'message' => 'User registered and logged in successfully',
        'user' => $user
    ], 201);
});

Route::post('/login', function (Request $request) {
    $validated = $request->validate([
        'email' => 'required|string|email|max:255',
        'password' => 'required|string|min:6',
    ]);

    if (!Auth::attempt($validated, $request->boolean('remember'))) {
        return response()->json([
            'message' => 'Invalid credentials',
        ], 422);
    }

    $request->session()->regenerate();

    return response()->json([
        'message' => 'Logged in successfully',
        'user' => $request->user()
    ]);
});

Route::middleware('auth:sanctum')->group(function () {
   Route::get('/user', function (Request $request) {
       return $request->user();
   });

   Route::post('/logout', function (Request $request) {
       Auth::guard('web')->logout();

       $request->session()->invalidate();
       $request->session()->regenerateToken();

       return response()->json([
           'message' => 'Logged out successfully',
       ]);
   });
});
