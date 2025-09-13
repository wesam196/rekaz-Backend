<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlobController;

Route::middleware('auth:sanctum')->group(function () {

    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::put('/v1/blobs', [BlobController::class, 'store']);
    Route::get('/v1/blobs/{id}', [BlobController::class, 'show']);
    Route::delete('/v1/blobs/{id}', [BlobController::class, 'delete']);
});



Route::post('/register', function(Request $request){
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6'
    ]);

    $user = \App\Models\User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
    ]);

    return response()->json($user);
});


Route::post('/token', function(Request $request){
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    $user = \App\Models\User::where('email', $request->email)->first();

    if (!$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json(['token' => $token]);
});



