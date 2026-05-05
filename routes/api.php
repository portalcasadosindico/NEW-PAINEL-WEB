<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->post('/upload-image', function (Request $request) {
    $request->validate([
        'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($request->hasFile('logo')) {
        // Armazena a imagem dentro da pasta public (storage/app/public/...)
        $path = $request->file('logo')->store('user-5000/afiliado/logo', 'public');

        return response()->json([
            'success' => true,
            'path' => $path,
            'url' => Storage::url($path) // retorna URL pública da imagem
        ]);
    }

    return response()->json(['success' => false, 'message' => 'Arquivo não enviado'], 400);
});
