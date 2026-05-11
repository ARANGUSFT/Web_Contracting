<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Subcontractors;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\EventCalendarController;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\FcmController;
use App\Http\Controllers\RepairTicketController;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ── Auth ──────────────────────────────────────────────────────────────────
Route::post('/login', function (Request $request) {
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    $user = Subcontractors::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    if (!$user->is_active) {
        return response()->json(['message' => 'Your account has been deactivated.'], 403);
    }

    $token = $user->createToken('mobile')->plainTextToken;

    return response()->json([
        'user' => [
            'id'           => $user->id,
            'name'         => $user->name,
            'last_name'    => $user->last_name,
            'email'        => $user->email,
            'company_name' => $user->company_name,
        ],
        'token' => $token,
    ]);
});

// ── Trabajos (jobs, emergencies, repairs) ─────────────────────────────────
Route::get('/trabajos-asignados/{id}',        [EventCalendarController::class, 'trabajosAsignados']);
Route::get('/trabajos-detalle/{type}/{id}',   [EventCalendarController::class, 'showTrabajo']);
Route::patch('/trabajos/{type}/{id}/status',  [EventCalendarController::class, 'updateStatus']);

// ── Repair Tickets ────────────────────────────────────────────────────────
Route::patch('/repair-tickets/{id}/status',     [RepairTicketController::class, 'updateStatusApi']);
Route::post('/repair-tickets/{id}/crew-photos', [FotoController::class, 'storeCrewPhotos']);

// ── Fotos (job_request | emergency | repair) ──────────────────────────────
Route::get('/fotos/{tipo}/{id}',    [FotoController::class, 'index']);
Route::post('/fotos',               [FotoController::class, 'store']);
Route::delete('/fotos/{tipo}/{id}', [FotoController::class, 'destroy']);

// ── Notes / Chat (requiere auth) ──────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/calendar/notes', [EventCalendarController::class, 'fetchNotes']);
    Route::post('/calendar/note', [EventCalendarController::class, 'storeNote']);
});

// ── Items / Files ─────────────────────────────────────────────────────────
Route::get('/items/{type}/{id}',        [EventCalendarController::class, 'show']);
Route::get('/items/{type}/{id}/files',  [EventCalendarController::class, 'files']);
Route::get('/files/inline',             [EventCalendarController::class, 'inlineFile']);
Route::get('/files/download',           [EventCalendarController::class, 'downloadFile']);

// ── Pagos ─────────────────────────────────────────────────────────────────
Route::get('/pagos/{subcontractor_id}', [EventCalendarController::class, 'pagos']);

// ── FCM Push Notifications ────────────────────────────────────────────────
Route::post('/fcm-token',   [FcmController::class, 'store']);
Route::delete('/fcm-token', [FcmController::class, 'destroy']);

// ── User autenticado ──────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});