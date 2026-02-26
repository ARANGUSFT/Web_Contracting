<?php

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\SubcontractorsController;
use App\Http\Controllers\EventCalendarController;
use App\Http\Controllers\CrewController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\ChatController;

use App\Http\Controllers\CompanyLocationController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemCategoryController;

use App\Http\Controllers\LocationItemPriceController;

use App\Http\Controllers\InvoiceController;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Team\ProfileTeamController;
use App\Http\Controllers\Auth\TeamLoginController;

// Contratista
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\EmergenciesController;
use App\Http\Controllers\JobRequestController;
use App\Http\Controllers\CalendarController;

// Roles
use App\Http\Controllers\Seller\SellerDashboardController; 
use App\Http\Controllers\Guest\GuestDashboardController;
use App\Http\Controllers\Manager\ManagerDashboardController;
use App\Http\Controllers\Crew\CrewDashboardController;
use App\Http\Controllers\ProjectManager\ProjectDashboardController;
use App\Http\Controllers\CompanyAdmin\CompanyAdminDashboardController;

// Acciones
use App\Http\Controllers\LeadMessageController;
use App\Http\Controllers\LeadImageController;
use App\Http\Controllers\LeadFilesController;
use App\Http\Controllers\LeadFinanzaController;
use App\Http\Controllers\LeadExpensesController;
use App\Http\Controllers\QuoteController;



// Página de inicio
Route::get('/', function () {
    return view('welcome');
});

// Dashboard general (para usuarios autenticados en "users")
Route::get('/dashboard', [LeadController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/leads/{lead_id}/chat', function ($lead_id) {
    return view('leads.chat', ['lead_id' => $lead_id]);
})->name('lead.chat')->middleware('auth');


  Route::get('/leads/{lead_id}/images/gallery', function ($lead_id) {
    return view('leads.gallery', ['lead_id' => $lead_id]);
})->name('lead.images.gallery')->middleware('auth');


Route::get('/superadmin/login', [AdminLoginController::class, 'showLoginForm'])->name('superadmin.login');
Route::post('/superadmin/login', [AdminLoginController::class, 'login']);
Route::post('/superadmin/logout', [AdminLoginController::class, 'logout'])->name('superadmin.logout');


Route::get('/pending-approval', function () {
    return view('auth.pending-approval');
})->name('pending.approval');


Route::middleware(['auth', 'is-admin'])
    ->prefix('superadmin')
    ->as('superadmin.')
    ->group(function () {

    /* ==========================================================
     | DASHBOARD
     ========================================================== */
    Route::get('/', fn () => redirect()->route('superadmin.users.index'))->name('dashboard');


    /* ==========================================================
     | USERS APPROVAL (PENDING)
     ========================================================== */
    Route::get('users/pending', [AdminUserController::class, 'pendingUsers'])->name('users.pending');
    Route::post('users/{user}/approve', [AdminUserController::class, 'approveUser'])->name('users.approve');
    Route::post('users/{user}/reject', [AdminUserController::class, 'rejectUser'])->name('users.reject');


    /* ==========================================================
     | USERS / COMPANIES
     ========================================================== */
    Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Contractors
    Route::get('contractors', [AdminUserController::class, 'contractors'])->name('users.contractors');
    Route::get('contractors/{user}/edit', [AdminUserController::class, 'editContractors'])->name('contractors.edit');
    Route::put('contractors/{user}', [AdminUserController::class, 'updateContractors'])->name('contractors.update');
    Route::patch('contractors/{user}/toggle-active', [AdminUserController::class, 'toggleActive'])->name('contractors.toggle-active');
    Route::delete('contractors/{user}', [AdminUserController::class, 'destroyContractors'])->name('contractors.destroy');
    Route::get('contractors/filter', [AdminUserController::class, 'filter'])->name('contractors.filter');
    Route::delete('contractors/{user}/documents/{index}',[AdminUserController::class, 'deleteContractorDocument'])->name('contractors.documents.delete');


    /* ==========================================================
     | SUBCONTRACTORS
     ========================================================== */
    Route::get('subcontractors', [SubcontractorsController::class, 'index'])->name('subcontractors.index');
    Route::get('subcontractors/create', [SubcontractorsController::class, 'create'])->name('subcontractors.create');
    Route::post('subcontractors', [SubcontractorsController::class, 'store'])->name('subcontractors.store');
    Route::get('subcontractors/{subcontractor}/edit', [SubcontractorsController::class, 'edit'])->name('subcontractors.edit');
    Route::put('subcontractors/{subcontractor}', [SubcontractorsController::class, 'update'])->name('subcontractors.update');
    Route::delete('subcontractors/{subcontractor}', [SubcontractorsController::class, 'destroy'])->name('subcontractors.destroy');


    /* ==========================================================
     | ITEMS (GLOBAL CATALOG)
     ========================================================== */
    Route::get('items', [ItemController::class, 'index'])->name('items.index');
    Route::get('items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('items', [ItemController::class, 'store'])->name('items.store');
    Route::get('items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('items/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');


        
    Route::resource('item-categories',ItemCategoryController::class)->except(['show'])->names('item-categories');


    /* ==========================================================
     | COMPANY LOCATIONS (EMPRESA + ESTADO)
     ========================================================== */
    // ================= LOCATIONS (GLOBAL) =================
    Route::get('locations', [CompanyLocationController::class, 'index'])->name('locations.index');
    Route::get('locations/create', [CompanyLocationController::class, 'create'])->name('locations.create');
    Route::post('locations', [CompanyLocationController::class, 'store'])->name('locations.store');
    Route::get('locations/{location}/edit', [CompanyLocationController::class, 'edit'])->name('locations.edit');
    Route::put('locations/{location}', [CompanyLocationController::class, 'update'])->name('locations.update');
    Route::delete('locations/{location}', [CompanyLocationController::class, 'destroy'])->name('locations.destroy');

    // ================= LOCATIONS PER COMPANY (🔥 PRINCIPAL) =================
    Route::get('companies/{company}/locations',[CompanyLocationController::class, 'manage'])->name('companies.locations.manage');
    Route::post('companies/{company}/locations',[CompanyLocationController::class, 'storeForCompany'])->name('companies.locations.store');

    // ================= PRICES PER LOCATION =================
    Route::get('locations/{location}/prices',[LocationItemPriceController::class, 'index'])->name('locations.prices.index');

    Route::post('locations/{location}/prices',[LocationItemPriceController::class, 'store'])->name('locations.prices.store');

    // ================= AJAX =================
    Route::get('companies/{company}/locations/ajax',[CompanyLocationController::class, 'byCompany'])->name('companies.locations.ajax');




    /* ==========================================================
     | CREWS
     ========================================================== */
    Route::get('crews', [CrewController::class, 'index'])->name('crew.index');
    Route::get('crews/create', [CrewController::class, 'create'])->name('crew.create');
    Route::post('crews', [CrewController::class, 'store'])->name('crew.store');
    Route::get('crews/{crew}/edit', [CrewController::class, 'edit'])->name('crew.edit');
    Route::put('crews/{crew}', [CrewController::class, 'update'])->name('crew.update');
    Route::delete('crews/{crew}', [CrewController::class, 'destroy'])->name('crew.destroy');
    Route::get('crews/{crew}', [CrewController::class, 'show'])->name('crew.show');
    Route::get('crews/{crew}/assign', [CrewController::class, 'assign'])->name('crew.assign');
    Route::post('crews/{crew}/assign', [CrewController::class, 'assignStore'])->name('crew.assign.store');


  


    /* ==========================================================
     | CALENDAR / EVENTS
     ========================================================== */
    Route::get('calendar', [EventCalendarController::class,'index'])->name('calendar.index');
    Route::get('calendar/events', [EventCalendarController::class,'events'])->name('calendar.events');
    Route::get('calendar/event/{type}/{id}', [EventCalendarController::class,'show'])->name('calendar.show');
    Route::post('calendar/assign', [EventCalendarController::class,'assignCrew'])->name('calendar.assign');
    Route::post('calendar/company/color', [EventCalendarController::class,'updateColor'])->name('calendar.company.updateColor');
    Route::post('calendar/company/update-visibility', [EventCalendarController::class,'updateVisibility'])->name('calendar.company.updateVisibility');
    Route::post('calendar/note', [EventCalendarController::class, 'storeNote'])->name('calendar.storeNote');
    Route::get('calendar/notes', [EventCalendarController::class, 'fetchNotes'])->name('calendar.fetchNotes');


    /* ==========================================================
     | PHOTOS
     ========================================================== */
    Route::prefix('photos')->as('photos.')->group(function () {
        Route::get('projects', [FotoController::class, 'projects'])->name('projects');
        Route::get('{tipo}/{id}/view', [FotoController::class, 'view'])->name('view');
        Route::post('share', [FotoController::class, 'createShareWeb'])->name('share');
        Route::post('unshare', [FotoController::class, 'revokeShareWeb'])->name('unshare');
    });


    /* ==========================================================
     | CHAT
     ========================================================== */
    Route::get('chat', [ChatController::class, 'chatView'])->name('chat.view');
    Route::get('chat/{userId}', [ChatController::class, 'index'])->name('chat.messages');
    Route::post('chat/send', [ChatController::class, 'send'])->name('chat.send');
    Route::post('chat/{userId}/read', [ChatController::class, 'markRead'])->name('chat.read');
    Route::get('chat-unread/count', [ChatController::class, 'unreadCount'])->name('chat.unread');
    Route::get('chat-users', [ChatController::class, 'users'])->name('chat.users');


    /* ==========================================================
     | INSURANCE
     ========================================================== */
    Route::get('subcontractors/insurances', [InsuranceController::class,'index'])->name('subcontractors.insurances.index');
    Route::get('subcontractors/{sub}/insurances/create', [InsuranceController::class,'create'])->name('subcontractors.insurances.create');
    Route::post('subcontractors/{sub}/insurances', [InsuranceController::class,'store'])->name('subcontractors.insurances.store');
    Route::get('subcontractors/{sub}/insurances/{ins}/edit', [InsuranceController::class, 'edit'])->name('subcontractors.insurances.edit');
    Route::put('subcontractors/{sub}/insurances/{ins}', [InsuranceController::class, 'update'])->name('subcontractors.insurances.update');
    Route::delete('subcontractors/{sub}/insurances/{ins}', [InsuranceController::class,'destroy'])->name('subcontractors.insurances.destroy');


    /* ==========================================================
     | INVOICES
     ========================================================== */
    Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::put('invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::get('invoices/location/{location}/items',[InvoiceController::class, 'itemsByLocation'])->name('invoices.items.by-location');
    Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');








    Route::get('invoices/{invoice}/prepare', [InvoiceController::class, 'prepareInvoice'])->name('invoices.prepare');

    Route::post('invoices/{invoice}/generate-custom-pdf', [InvoiceController::class, 'generateCustomPdf'])->name('invoices.generateCustomPdf');

    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');

});



















Route::get('/g/{token}', [FotoController::class, 'publicGallery'])->name('photos.public');


// 🔹 Rutas para Administradores (Usuarios en la tabla "users")
Route::middleware(['auth:web', 'approved'])->group(function () {

    // Perfil de usuario (admin)
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/company-documents/{index}', [ProfileController::class, 'deleteCompanyDocument'])->name('company-documents.delete');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Project MG
      // JobReq
        Route::get('/jobs/create', [JobRequestController::class, 'create'])->name('jobs.create'); 
        Route::post('/jobs/store', [JobRequestController::class, 'store'])->name('jobs.store'); 
        Route::get('/jobs/{id}', [JobRequestController::class, 'show'])->name('jobs.show');
        Route::get('/jobs/{job}/edit', [JobRequestController::class, 'edit'])->name('jobs.edit');
        Route::put('/jobs/{job}', [JobRequestController::class, 'update'])->name('jobs.update');
        Route::delete('/jobs/{job}/files/{field}/{file}', [JobRequestController::class, 'deleteFile'])->where('file', '.*')->name('jobs.files.delete');
        Route::delete('/jobs/{job}', [JobRequestController::class, 'destroy'])->name('jobs.destroy');
      // Emergency
        Route::post('/emergency', [EmergenciesController::class, 'store'])->name('emergency.store');
        Route::get('/emergency', [EmergenciesController::class, 'form'])->name('emergency.form');
        Route::get('/emergency/{id}', [EmergenciesController::class, 'show'])->name('emergency.show');
        Route::get('/emergency/{emergency}/edit', [EmergenciesController::class, 'edit'])->name('emergency.edit');
        Route::put('/emergency/{emergency}', [EmergenciesController::class, 'update'])->name('emergency.update');
        Route::delete('/emergency/file/delete', [EmergenciesController::class, 'deleteFile'])->name('emergency.file.delete');
        Route::delete('/emergency/{emergency}', [EmergenciesController::class, 'destroy'])->name('emergency.destroy');     
    // 📅 Calendar
        Route::get('/calendar', fn () => view('leads.pg.calendar'))->name('calendar.view');
        Route::get('/calendar/data', [CalendarController::class, 'calendarData'])->name('calendar.data');
        // Formulario approved
        Route::post('/leads/{id}/submit-approved-data', [LeadController::class, 'submitApprovedData'])->name('leads.submitApprovedData');
  

    // 📩 Vista principal (lista de admins para chatear)
        Route::get('/chat', [ChatController::class, 'chatView'])->name('user.chat.view');
        Route::get('/chat/{userId}', [ChatController::class, 'index'])->name('user.chat.messages');
        Route::post('/chat/send', [ChatController::class, 'send'])->name('user.chat.send');
        Route::post('/chat/{userId}/read', [ChatController::class, 'markRead'])->name('user.chat.read');
        Route::get('/chat-unread/count', [ChatController::class, 'unreadCount'])->name('user.chat.unread');

    // CRUD de Leads
        Route::resource('/leads', LeadController::class);
        Route::get('/listleads', [LeadController::class, 'index'])->name('leads.index');
        // Estados Lead
        // Route::post('/leads/{id}/update-status', [LeadController::class, 'updateStatus'])->name('leads.updateStatus');
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
    // Lead Images
        Route::get('/leads/{lead}/images', [LeadImageController::class, 'index'])->name('lead.images.index');
        Route::post('/leads/images', [LeadImageController::class, 'store'])->name('lead.images.store');
        Route::delete('/leads/images/{id}', [LeadImageController::class, 'destroy'])->name('lead.images.destroy');
        Route::post('/leads/images/bulk-delete', [LeadImageController::class, 'bulkDelete'])->name('lead.images.bulkDelete');
        Route::delete('/leads/{lead}/images/delete-all', [LeadImageController::class, 'deleteAll'])->name('lead.images.deleteAll');
    // Actualizar y Elimianr Documentos
        Route::post('/leads/{lead}/files', [LeadFilesController::class, 'store'])->name('leads.files.store');
        Route::delete('/leads/files/{leadFile}', [LeadFilesController::class, 'destroy'])->name('leads.files.destroy');
        Route::post('/leads/{lead}/folders', [LeadFilesController::class, 'storeFolder'])->name('leads.folders.store');
        Route::delete('/leads/folders/{folder}', [LeadFilesController::class, 'destroyFolder'])->name('leads.folders.destroy');
    // Contribution Panel 
        Route::put('/leads/{lead}/finanzas', [LeadFinanzaController::class, 'update'])->name('leads.finanzas.update');
        Route::post('/leads/{lead}/finanzas', [LeadFinanzaController::class, 'store'])->name('lead.finanzas.store');
        Route::delete('/leads/{lead}/finanzas/{finanza}', [LeadFinanzaController::class, 'destroy'])->name('lead.finanzas.destroy');
    // Expenses 
        Route::post('/lead-expenses', [LeadExpensesController::class, 'store'])->name('lead-expenses.store');
        Route::delete('/lead-expenses/{id}', [LeadExpensesController::class, 'destroy'])->name('lead-expenses.destroy');
    // Quotes 
        Route::get('/quotes/create', [QuoteController::class, 'create'])->name('quotes.create');
        Route::post('/quotes', [QuoteController::class, 'store'])->name('quotes.store');
        Route::delete('/quotes/{quote}', [QuoteController::class, 'destroy'])->name('quotes.destroy');
  
});


  

    // 🔹 Rutas de Autenticación para "users" (Administradores)
    require __DIR__.'/auth.php';

    // 🔹 Rutas de Autenticación para "team" (Vendedores/Trabajadores)
    Route::get('/team/login', [TeamLoginController::class, 'showLoginForm'])->name('team.login');
    Route::post('/team/login', [TeamLoginController::class, 'login']);
    Route::post('/team/logout', [TeamLoginController::class, 'logout'])->name('team.logout');







// 🔹 Panel de Vendedores (Solo para usuarios autenticados en "team")
Route::prefix('seller')->middleware('auth:team')->group(function () {
    // Perfil Seller
        Route::get('/seller/dashboard', [SellerDashboardController::class, 'index'])->name('seller.dashboard');
        Route::get('/seller/leads/{id}', [SellerDashboardController::class, 'show'])->name('seller.leads.show');
        Route::get('/seller/leads/{id}/edit', [SellerDashboardController::class, 'edit'])->name('seller.leads.edit');
    // Crear Lead
        // Mostrar el formulario (GET)
        Route::get('seller/create/lead', [SellerDashboardController::class, 'create'])->name('seller.create');
        // Procesar el formulario (POST)
        Route::post('seller/create/lead', [SellerDashboardController::class, 'store'])->name('seller.store');
    // Actualziar Lead
        Route::match(['put', 'post'], '/seller/leads/{id}', [SellerDashboardController::class, 'update'])->name('seller.leads.update');
    // Actualizar estado (l,p,a,c,i)
        Route::post('/seller/leads/{id}/update-status', [SellerDashboardController::class, 'updateStatus'])->name('seller.leads.updateStatus');


    // Perfil
    Route::get('/profile', [ProfileTeamController::class, 'edit'])->name('seller.profile.edit');
    Route::put('/profile', [ProfileTeamController::class, 'update'])->name('seller.profile.update');
    Route::put('/profile/password', [ProfileTeamController::class, 'updatePassword'])->name('seller.profile.password.update');
});

// 🔹 Panel de Invitados (guest)
Route::prefix('guest')->middleware(['auth:team', 'team.active'])->group(function () {
    Route::get('/dashboard', [GuestDashboardController::class, 'index'])->name('guest.dashboard');
    Route::get('/view/guest/{id}', [GuestDashboardController::class, 'show'])->name('guest.view');
    Route::post('/guest/{lead}/assignstatus', [GuestDashboardController::class, 'assignStatusManage'])->name('guest.assignstatus');

    // Perfil
    Route::get('/profile', [ProfileTeamController::class, 'edit'])->name('guest.profile.edit');
    Route::put('/profile', [ProfileTeamController::class, 'update'])->name('guest.profile.update');
    Route::put('/profile/password', [ProfileTeamController::class, 'updatePassword'])->name('guest.profile.password.update');
});

// 🔹 Panel de Manager
Route::prefix('manager')->middleware(['auth:team', 'team.active'])->group(function () {
    Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('manager.dashboard');
    Route::get('/view/manage/{id}', [ManagerDashboardController::class, 'show'])->name('manager.manage');
    Route::post('/manager/manage/{lead}/assignstatus', [ManagerDashboardController::class, 'assignStatusManage'])->name('manager.assignstatus');
    Route::get('/calendar', [ManagerDashboardController::class, 'calendar'])->name('manager.calendar');
    Route::post('/leads/{lead}/assignstatus', [ManagerDashboardController::class, 'assignStatus'])->name('manager.assignstatus');
    Route::post('/leads/{id}/submit-approved-data', [ManagerDashboardController::class, 'submitApprovedData'])->name('manager.submitApprovedData');

    // Perfil
    Route::get('/profile', [ProfileTeamController::class, 'edit'])->name('manager.profile.edit');
    Route::put('/profile', [ProfileTeamController::class, 'update'])->name('manager.profile.update');
    Route::put('/profile/password', [ProfileTeamController::class, 'updatePassword'])->name('manager.profile.password.update');
});


// 🔹 Panel de Crew
Route::prefix('crew')->middleware(['auth:team', 'team.active'])->group(function () {
    Route::get('/dashboard', [CrewDashboardController::class, 'index'])->name('crew.dashboard');
    Route::get('/calendar', [CrewDashboardController::class, 'calendar'])->name('crew.calendar');
    
    // Gestión de Leads
    Route::get('/leads', [CrewDashboardController::class, 'index'])->name('crew.leads'); // Alternativa para listar leads
    Route::get('/leads/{id}', [CrewDashboardController::class, 'show'])->name('crew.view');
    Route::put('/leads/{id}/status', [CrewDashboardController::class, 'updateLeadStatus'])->name('crew.leads.updateStatus');
    
    // Perfil
    Route::get('/profile', [ProfileTeamController::class, 'edit'])->name('crew.profile.edit');
    Route::put('/profile', [ProfileTeamController::class, 'update'])->name('crew.profile.update');
    Route::put('/profile/password', [ProfileTeamController::class, 'updatePassword'])->name('crew.profile.password.update');
});

// 🔹 Panel de Project Manager
Route::prefix('project')->middleware(['auth:team', 'team.active'])->group(function () {
    Route::get('/dashboard', [ProjectDashboardController::class, 'index'])->name('project.dashboard');
    Route::get('/calendar', [ProjectDashboardController::class, 'calendar'])->name('project.calendar');
    Route::get('/leads/{id}', [ProjectDashboardController::class, 'show'])->name('project.view');

    // Perfil
    Route::get('/profile', [ProfileTeamController::class, 'edit'])->name('project.profile.edit');
    Route::put('/profile', [ProfileTeamController::class, 'update'])->name('project.profile.update');
    Route::put('/profile/password', [ProfileTeamController::class, 'updatePassword'])->name('project.profile.password.update');
});

// 🔹 Panel de Company Admin
Route::prefix('admin')->middleware(['auth:team', 'team.active'])->group(function () {
    Route::get('/dashboard', [CompanyAdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Perfil
    Route::get('/profile', [ProfileTeamController::class, 'edit'])->name('admin.profile.edit');
    Route::put('/profile', [ProfileTeamController::class, 'update'])->name('admin.profile.update');
    Route::put('/profile/password', [ProfileTeamController::class, 'updatePassword'])->name('admin.profile.password.update');
});
