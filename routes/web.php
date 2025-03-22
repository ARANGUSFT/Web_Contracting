<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\Auth\TeamLoginController;
use App\Http\Controllers\Seller\SellerDashboardController; // ❗ Falta incluir este controlador
use App\Http\Controllers\LeadMessageController;
use App\Http\Controllers\LeadImageController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Página de inicio
Route::get('/', function () {
    return view('welcome');
});

// Dashboard general (para usuarios autenticados en "users")
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/leads/{lead_id}/chat', function ($lead_id) {
    return view('leads.chat', ['lead_id' => $lead_id]);
})->name('lead.chat')->middleware('auth');


  Route::get('/leads/{lead_id}/images/gallery', function ($lead_id) {
    return view('leads.gallery', ['lead_id' => $lead_id]);
})->name('lead.images.gallery')->middleware('auth');



// 🔹 Rutas para Administradores (Usuarios en la tabla "users")
Route::middleware(['auth:web'])->group(function () {
    // Perfil de usuario (admin)
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD de Leads
        Route::resource('/leads', LeadController::class);
        Route::get('/listleads', [LeadController::class, 'index'])->name('leads.index');
        // Estados Lead
        Route::post('/leads/{id}/update-status', [LeadController::class, 'updateStatus'])->name('leads.updateStatus');
        Route::post('/leads/{lead}/assignstatus', [LeadController::class, 'assignStatus'])->name('leads.assignstatus');
        // Asignado Lead
        Route::put('/leads/{id}/assign', [LeadController::class, 'assignSales'])->name('leads.assignSales');
        // Actualziar Lead
        Route::get('/leads/{lead}/edit', [LeadController::class, 'edit'])->name('leads.edit');
        Route::patch('/leads/{lead}', [LeadController::class, 'update'])->name('leads.update');

    // CRUD de Team (Trabajadores/Vendedores)
        Route::resource('/teams', TeamController::class);

});




// 🔒 Rutas que requieren autenticación con usuarios "web" o "team"
Route::middleware(['auth:web,team'])->group(function () {

    // Lead Messages (Chat)
    Route::get('/leads/{lead_id}/messages', [LeadMessageController::class, 'index'])->name('lead.messages.index');
    Route::post('/leads/messages', [LeadMessageController::class, 'store'])->name('lead.messages.store');
    Route::get('/leads/{lead_id}/gallery', [LeadImageController::class, 'index'])->name('leads.gallery');
    // Lead Images
    Route::post('/leads/images', [LeadImageController::class, 'store'])->name('lead.images.store');
    Route::get('/leads/{lead_id}/images', [LeadImageController::class, 'index'])->name('lead.images.index');
    Route::delete('/leads/images/{id}', [LeadImageController::class, 'destroy'])->name('lead.images.destroy');

});




    // 🔹 Rutas de Autenticación para "users" (Administradores)
    require __DIR__.'/auth.php';

    // 🔹 Rutas de Autenticación para "team" (Vendedores/Trabajadores)
    Route::get('/team/login', [TeamLoginController::class, 'showLoginForm'])->name('team.login');
    Route::post('/team/login', [TeamLoginController::class, 'login']);
    Route::post('/team/logout', [TeamLoginController::class, 'logout'])->name('team.logout');




// 🔹 Panel de Vendedores (Solo para usuarios autenticados en "team")
Route::middleware(['auth:team'])->group(function () {
    // Perfil Seller
    Route::get('/seller/dashboard', [SellerDashboardController::class, 'index'])->name('seller.dashboard');
    Route::get('/seller/leads/{id}', [SellerDashboardController::class, 'show'])->name('seller.leads.show');
    Route::get('/seller/leads/{id}/edit', [SellerDashboardController::class, 'edit'])->name('seller.leads.edit');
    Route::match(['put', 'post'], '/seller/leads/{id}', [SellerDashboardController::class, 'update'])->name('seller.leads.update');
    Route::post('/seller/leads/{id}/update-status', [SellerDashboardController::class, 'updateStatus'])->name('seller.leads.updateStatus');
    Route::put('/seller/leads/{lead}/documents/update', [SellerDashboardController::class, 'updateDocuments'])->name('seller.leads.updateDocuments');
    Route::delete('/seller/leads/{lead}/delete-file', [SellerDashboardController::class, 'deleteFile'])->name('seller.leads.delete-file');
    


});
