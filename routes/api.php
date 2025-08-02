<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalendarAllController;
use App\Models\Subcontractors;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\EventCalendarController;
use App\Http\Controllers\FotoController;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = \App\Models\Subcontractors::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    if (!$user->is_active) {
        return response()->json(['message' => 'Your account has been deactivated.'], 403);
    }

    $token = $user->createToken('mobile')->plainTextToken;

    return response()->json([
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'company_name' => $user->company_name,
        ],
        'token' => $token
    ]);
});

Route::get('/trabajos-asignados/{id}', [EventCalendarController::class, 'trabajosAsignados']);
Route::get('/trabajos-detalle/{type}/{id}', [EventCalendarController::class, 'showTrabajo']);


Route::get('fotos/{tipo}/{id}', [FotoController::class, 'index']);
Route::post('fotos', [FotoController::class, 'store']);

Route::middleware('auth:sanctum')->post('/calendar/note', [EventCalendarController::class, 'storeNote']);
Route::middleware('auth:sanctum')->get('/calendar/notes', [EventCalendarController::class, 'fetchNotes']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Ruta para obtener eventos del calendario
Route::get('/calendar-all', [CalendarAllController::class, 'index']);