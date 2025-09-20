<?php

use App\Http\Controllers\Api\EmailApiController;
use App\Http\Controllers\Api\SettingApiController;
use App\Http\Controllers\Api\TemplateApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

Route::post('/login', function (Request $request) {
  $request->validate([
    'email' => 'required|email',
    'password' => 'required',
    'device_name' => 'nullable|string',
  ]);

  $user = User::where('email', $request->email)->first();

  if (!$user || !Hash::check($request->password, $user->password)) {
    throw ValidationException::withMessages([
      'email' => ['The provided credentials are incorrect.'],
    ]);
  }

  $deviceName = $request->device_name ?? 'api-token';

  return response()->json([
    'token' => $user->createToken($deviceName)->plainTextToken,
    'user' => $user
  ]);
});

Route::post('/logout', function (Request $request) {
  $request->user()->currentAccessToken()->delete();

  return response()->json(['message' => 'Logged out successfully']);
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
  return $request->user();
});


Route::group(['middleware' => 'auth:sanctum'], function () {
  Route::resource('settings', SettingApiController::class);
  Route::resource('templates', TemplateApiController::class);
  Route::resource('emails', EmailApiController::class);
});