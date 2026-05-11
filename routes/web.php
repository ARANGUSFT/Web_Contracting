<?php

// ============================================================
// IMPORTS
// ============================================================
use Illuminate\Support\Facades\Route;

// Auth
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\TeamLoginController;

// Core Admin
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Team\ProfileTeamController;

// Leads & Sales
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadMessageController;
use App\Http\Controllers\LeadImageController;
use App\Http\Controllers\LeadFilesController;
use App\Http\Controllers\LeadFinanzaController;
use App\Http\Controllers\LeadExpensesController;
use App\Http\Controllers\QuoteController;

// Jobs & Emergencies
use App\Http\Controllers\JobRequestController;
use App\Http\Controllers\EmergenciesController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\RepairTicketController;
use App\Http\Controllers\EventCalendarController;

// Team & Crews
use App\Http\Controllers\TeamController;
use App\Http\Controllers\CrewController;
use App\Http\Controllers\SubcontractorsController;
use App\Http\Controllers\InsuranceController;

// Catalog & Pricing
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemCategoryController;
use App\Http\Controllers\CompanyLocationController;
use App\Http\Controllers\LocationItemPriceController;

// Invoices
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\WeeklyAccountingController;

// Media & Docs
use App\Http\Controllers\FotoController;
use App\Http\Controllers\ChatController;

// Role Dashboards
use App\Http\Controllers\Seller\SellerDashboardController;
use App\Http\Controllers\Guest\GuestDashboardController;
use App\Http\Controllers\Manager\ManagerDashboardController;
use App\Http\Controllers\Crew\CrewDashboardController;
use App\Http\Controllers\ProjectManager\ProjectDashboardController;
use App\Http\Controllers\CompanyAdmin\CompanyAdminDashboardController;




// ============================================================
// PUBLIC ROUTES
// ============================================================

Route::get('/', fn () => view('welcome'));

Route::get('/pending-approval', fn () => view('auth.pending-approval'))
    ->name('pending.approval');

Route::get('/g/{token}', [FotoController::class, 'publicGallery'])
    ->name('photos.public');


      

// ============================================================
// AUTHENTICATION
// ============================================================

// --- Superadmin ---
Route::get('/superadmin/login',  [AdminLoginController::class, 'showLoginForm'])->name('superadmin.login');
Route::post('/superadmin/login', [AdminLoginController::class, 'login']);
Route::post('/superadmin/logout',[AdminLoginController::class, 'logout'])->name('superadmin.logout');

// --- Team (sellers / workers) ---
Route::get('/team/login',  [TeamLoginController::class, 'showLoginForm'])->name('team.login');
Route::post('/team/login', [TeamLoginController::class, 'login']);
Route::post('/team/logout',[TeamLoginController::class, 'logout'])->name('team.logout');

// --- Web users (admins) ---
require __DIR__.'/auth.php';




// ============================================================
// SUPERADMIN PANEL  (auth + is-admin)
// ============================================================

Route::middleware(['auth', 'is-admin'])
    ->prefix('superadmin')
    ->as('superadmin.')
    ->group(function () {

    Route::get('/', fn () => redirect()->route('superadmin.users.index'))->name('dashboard');

    // ── User Approval ──────────────────────────────────────────
    Route::get('users/pending',           [AdminUserController::class, 'pendingUsers'])->name('users.pending');
    Route::post('users/{user}/approve',   [AdminUserController::class, 'approveUser'])->name('users.approve');
    Route::post('users/{user}/reject',    [AdminUserController::class, 'rejectUser'])->name('users.reject');

    // ── Users CRUD ─────────────────────────────────────────────
    Route::get('users',              [AdminUserController::class, 'index'])->name('users.index');
    Route::get('users/create',       [AdminUserController::class, 'create'])->name('users.create');
    Route::post('users',             [AdminUserController::class, 'store'])->name('users.store');
    Route::get('users/{user}/edit',  [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}',       [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}',    [AdminUserController::class, 'destroy'])->name('users.destroy');

    // ── Contractors ────────────────────────────────────────────
    Route::get('contractors',                              [AdminUserController::class, 'contractors'])->name('users.contractors');
    Route::get('contractors/filter',                       [AdminUserController::class, 'filter'])->name('contractors.filter');
    Route::get('contractors/{user}/edit',                  [AdminUserController::class, 'editContractors'])->name('contractors.edit');
    Route::put('contractors/{user}',                       [AdminUserController::class, 'updateContractors'])->name('contractors.update');
    Route::patch('contractors/{user}/toggle-active',       [AdminUserController::class, 'toggleActive'])->name('contractors.toggle-active');
    Route::delete('contractors/{user}',                    [AdminUserController::class, 'destroyContractors'])->name('contractors.destroy');
    Route::delete('contractors/{user}/documents/{index}',  [AdminUserController::class, 'deleteContractorDocument'])->name('contractors.documents.delete');

    // ── Subcontractors ─────────────────────────────────────────
    Route::get('subcontractors',                     [SubcontractorsController::class, 'index'])->name('subcontractors.index');
    Route::get('subcontractors/create',              [SubcontractorsController::class, 'create'])->name('subcontractors.create');
    Route::post('subcontractors',                    [SubcontractorsController::class, 'store'])->name('subcontractors.store');
    Route::get('subcontractors/{subcontractor}/edit',[SubcontractorsController::class, 'edit'])->name('subcontractors.edit');
    Route::put('subcontractors/{subcontractor}',     [SubcontractorsController::class, 'update'])->name('subcontractors.update');
    Route::delete('subcontractors/{subcontractor}',  [SubcontractorsController::class, 'destroy'])->name('subcontractors.destroy');

    // ── Insurance ──────────────────────────────────────────────
    Route::get('subcontractors/insurances',                            [InsuranceController::class, 'index'])->name('subcontractors.insurances.index');
    Route::get('subcontractors/{sub}/insurances/create',               [InsuranceController::class, 'create'])->name('subcontractors.insurances.create');
    Route::post('subcontractors/{sub}/insurances',                     [InsuranceController::class, 'store'])->name('subcontractors.insurances.store');
    Route::get('subcontractors/{sub}/insurances/{ins}/edit',           [InsuranceController::class, 'edit'])->name('subcontractors.insurances.edit');
    Route::put('subcontractors/{sub}/insurances/{ins}',                [InsuranceController::class, 'update'])->name('subcontractors.insurances.update');
    Route::delete('subcontractors/{sub}/insurances/{ins}',             [InsuranceController::class, 'destroy'])->name('subcontractors.insurances.destroy');

    // ── Crews ──────────────────────────────────────────────────
    Route::get('crews',                  [CrewController::class, 'index'])->name('crew.index');
    Route::get('crews/create',           [CrewController::class, 'create'])->name('crew.create');
    Route::post('crews',                 [CrewController::class, 'store'])->name('crew.store');
    Route::get('crews/{crew}/edit',      [CrewController::class, 'edit'])->name('crew.edit');
    Route::put('crews/{crew}',           [CrewController::class, 'update'])->name('crew.update');
    Route::delete('crews/{crew}',        [CrewController::class, 'destroy'])->name('crew.destroy');
    Route::get('crews/{crew}',           [CrewController::class, 'show'])->name('crew.show');
    Route::get('crews/{crew}/assign',    [CrewController::class, 'assign'])->name('crew.assign');
    Route::post('crews/{crew}/assign',   [CrewController::class, 'assignStore'])->name('crew.assign.store');

    // ── Items & Categories ─────────────────────────────────────
    Route::get('items',             [ItemController::class, 'index'])->name('items.index');
    Route::get('items/create',      [ItemController::class, 'create'])->name('items.create');
    Route::post('items',            [ItemController::class, 'store'])->name('items.store');
    Route::get('items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('items/{item}',      [ItemController::class, 'update'])->name('items.update');
    Route::delete('items/{item}',   [ItemController::class, 'destroy'])->name('items.destroy');
    Route::resource('item-categories', ItemCategoryController::class)->except(['show'])->names('item-categories');

    // ── Company Locations & Prices ─────────────────────────────
    Route::get('locations',                [CompanyLocationController::class, 'index'])->name('locations.index');
    Route::get('locations/create',         [CompanyLocationController::class, 'create'])->name('locations.create');
    Route::post('locations',               [CompanyLocationController::class, 'store'])->name('locations.store');
    Route::get('locations/{location}/edit',[CompanyLocationController::class, 'edit'])->name('locations.edit');
    Route::put('locations/{location}',     [CompanyLocationController::class, 'update'])->name('locations.update');
    Route::delete('locations/{location}',  [CompanyLocationController::class, 'destroy'])->name('locations.destroy');

    Route::get('companies/{company}/locations',      [CompanyLocationController::class, 'manage'])->name('companies.locations.manage');
    Route::post('companies/{company}/locations',     [CompanyLocationController::class, 'storeForCompany'])->name('companies.locations.store');
    Route::get('companies/{company}/locations/ajax', [CompanyLocationController::class, 'byCompany'])->name('companies.locations.ajax');

    Route::get('locations/{location}/prices',  [LocationItemPriceController::class, 'index'])->name('locations.prices.index');
    Route::post('locations/{location}/prices', [LocationItemPriceController::class, 'store'])->name('locations.prices.store');

    // ── Calendar (Admin/EventCalendar) ─────────────────────────
    Route::get('calendar',                          [EventCalendarController::class, 'index'])->name('calendar.index');
    Route::get('calendar/events',                   [EventCalendarController::class, 'events'])->name('calendar.events');
    Route::get('calendar/event/{type}/{id}',        [EventCalendarController::class, 'show'])->name('calendar.show');
    Route::post('calendar/assign',                  [EventCalendarController::class, 'assignCrew'])->name('calendar.assign');
    Route::post('calendar/company/color',           [EventCalendarController::class, 'updateColor'])->name('calendar.company.updateColor');
    Route::post('calendar/company/update-visibility',[EventCalendarController::class, 'updateVisibility'])->name('calendar.company.updateVisibility');
    Route::post('calendar/note',                    [EventCalendarController::class, 'storeNote'])->name('calendar.storeNote');
    Route::get('calendar/notes',                    [EventCalendarController::class, 'fetchNotes'])->name('calendar.fetchNotes');
    Route::get('calendar/pending-payments',         [EventCalendarController::class, 'pendingPayments'])->name('calendar.pendingPayments');

    Route::get('calendar/invoiceable-options',       [EventCalendarController::class, 'invoiceableOptions'])->name('calendar.invoiceable-options'); // ← nueva

    Route::patch('/jobs/{id}/payment',        [EventCalendarController::class, 'updatePayment'])->name('jobs.update-payment');
    Route::patch('/emergencies/{id}/payment', [EventCalendarController::class, 'updateEmergencyPayment'])->name('emergencies.update-payment');
    Route::get('/files/receipt/{type}/{id}',  [EventCalendarController::class, 'viewReceipt'])->name('receipt.view');


    // Dentro del grupo superadmin:
    Route::patch('calendar/status/{type}/{id}', [EventCalendarController::class, 'updateStatus'])->name('calendar.updateStatus');

    // Repair Tickets desde superadmin
    Route::get('repair-tickets/{repairTicket}/edit',              [RepairTicketController::class, 'edit'])->name('repair.edit');
    Route::put('repair-tickets/{repairTicket}',                   [RepairTicketController::class, 'update'])->name('repair.update');
    Route::post('repair-tickets/{repairTicket}/upload-photos',    [FotoController::class, 'storeAdminPhotos'])->name('repair.photos.upload');
    Route::delete('repair-tickets/{repairTicket}/photos/{index}', [RepairTicketController::class, 'deletePhoto'])->name('repair.photos.delete');
    Route::delete('repair-tickets/{repairTicket}',                [RepairTicketController::class, 'destroy'])->name('repair.destroy');
    Route::post('repair-tickets/{repairTicket}/payment',          [RepairTicketController::class, 'savePayment'])->name('repair.payment');  // ← agrega esta

    // ── Invoices ───────────────────────────────────────────────
    Route::get('invoices',                               [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/create',                        [InvoiceController::class, 'create'])->name('invoices.create');
    Route::get('invoices/linked',                        [InvoiceController::class, 'linked'])->name('invoices.linked');
    Route::get('invoices/work-order-info',               [InvoiceController::class, 'workOrderInfo'])->name('invoices.work-order-info'); // ← AGREGAR
    Route::get('invoices/location/{location}/items',     [InvoiceController::class, 'itemsByLocation'])->name('invoices.items.by-location');
    Route::post('invoices',                              [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('invoices/{invoice}',                     [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('invoices/{invoice}/edit',                [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::put('invoices/{invoice}',                     [InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('invoices/{invoice}',                  [InvoiceController::class, 'destroy'])->name('invoices.destroy');
    Route::get('invoices/{invoice}/prepare',             [InvoiceController::class, 'prepareInvoice'])->name('invoices.prepare');
    Route::post('invoices/{invoice}/generate-custom-pdf',[InvoiceController::class, 'generateCustomPdf'])->name('invoices.generateCustomPdf');
    Route::get('invoices/{invoice}/pdf',                 [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');


    // ── Weekly Accounting ─────────────────────────────────────
    Route::prefix('weekly-accounting')->as('weekly-accounting.')->group(function () {
        Route::get('/',                 [WeeklyAccountingController::class, 'index'])->name('index');
        Route::post('/save-costs',      [WeeklyAccountingController::class, 'saveCosts'])->name('save-costs');
        Route::post('/update-settings', [WeeklyAccountingController::class, 'updateSettings'])->name('update-settings');
        Route::post('/toggle-paid',     [WeeklyAccountingController::class, 'togglePaid'])->name('toggle-paid');
    });
    
    // ── Photos ─────────────────────────────────────────────────
    Route::prefix('photos')->as('photos.')->group(function () {
        Route::get('projects',         [FotoController::class, 'projects'])->name('projects');
        Route::get('{tipo}/{id}/view', [FotoController::class, 'view'])
            ->where('tipo', 'job_request|emergency|repair')   // ← agrega esto
            ->name('view');
        Route::post('share',           [FotoController::class, 'createShareWeb'])->name('share');
        Route::post('unshare',         [FotoController::class, 'revokeShareWeb'])->name('unshare');
    });

    // ── Chat (Superadmin) ──────────────────────────────────────
    Route::get('chat',                [ChatController::class, 'chatView'])->name('chat.view');
    Route::get('chat/{userId}',       [ChatController::class, 'index'])->name('chat.messages');
    Route::post('chat/send',          [ChatController::class, 'send'])->name('chat.send');
    Route::post('chat/{userId}/read', [ChatController::class, 'markRead'])->name('chat.read');
    Route::get('chat-unread/count',   [ChatController::class, 'unreadCount'])->name('chat.unread');
    Route::get('chat-users',          [ChatController::class, 'users'])->name('chat.users');
});




// ============================================================
// ADMIN / WEB USER PANEL  (auth:web + approved)
// ============================================================

Route::middleware(['auth:web', 'approved'])->group(function () {

    // ── Dashboard ──────────────────────────────────────────────
    Route::get('/dashboard', [LeadController::class, 'dashboard'])
        ->middleware('verified')
        ->name('dashboard');

    // ── Profile ────────────────────────────────────────────────
    Route::get('/profile',                      [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',                      [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',                   [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/company-documents/{index}', [ProfileController::class, 'deleteCompanyDocument'])->name('company-documents.delete');

    // ── Leads ──────────────────────────────────────────────────
    Route::resource('/leads', LeadController::class);
    Route::get('/listleads',                        [LeadController::class, 'index'])->name('leads.index');
    Route::post('/leads/{lead}/assignstatus',        [LeadController::class, 'assignStatus'])->name('leads.assignstatus');
    Route::put('/leads/{id}/assign',                 [LeadController::class, 'assignSales'])->name('leads.assignSales');
    Route::get('/leads/{lead}/edit',                 [LeadController::class, 'edit'])->name('leads.edit');
    Route::patch('/leads/{lead}',                    [LeadController::class, 'update'])->name('leads.update');
    Route::post('/leads/{id}/submit-approved-data',  [LeadController::class, 'submitApprovedData'])->name('leads.submitApprovedData');

    // ── Lead Chat ──────────────────────────────────────────────
    Route::get('/leads/{lead_id}/chat', fn ($lead_id) => view('leads.chat', compact('lead_id')))->name('lead.chat');
    Route::get('/leads/{lead_id}/images/gallery', fn ($lead_id) => view('leads.gallery', compact('lead_id')))->name('lead.images.gallery');

    // ── Jobs ───────────────────────────────────────────────────
    Route::get('/jobs/create',                              [JobRequestController::class, 'create'])->name('jobs.create');
    Route::post('/jobs/store',                              [JobRequestController::class, 'store'])->name('jobs.store');
    Route::get('/jobs/{id}',                                [JobRequestController::class, 'show'])->name('jobs.show');
    Route::get('/jobs/{job}/edit',                          [JobRequestController::class, 'edit'])->name('jobs.edit');
    Route::put('/jobs/{job}',                               [JobRequestController::class, 'update'])->name('jobs.update');
    Route::delete('/jobs/{job}',                            [JobRequestController::class, 'destroy'])->name('jobs.destroy');
    Route::delete('/jobs/{job}/files/{field}/{file}',       [JobRequestController::class, 'deleteFile'])->where('file', '.*')->name('jobs.files.delete');

    // ── Emergencies ────────────────────────────────────────────
    Route::get('/emergency',                    [EmergenciesController::class, 'form'])->name('emergency.form');
    Route::post('/emergency',                   [EmergenciesController::class, 'store'])->name('emergency.store');
    Route::get('/emergency/{id}',               [EmergenciesController::class, 'show'])->name('emergency.show');
    Route::get('/emergency/{emergency}/edit',   [EmergenciesController::class, 'edit'])->name('emergency.edit');
    Route::put('/emergency/{emergency}',        [EmergenciesController::class, 'update'])->name('emergency.update');
    Route::delete('/emergency/{emergency}',     [EmergenciesController::class, 'destroy'])->name('emergency.destroy');
    Route::delete('/emergency/file/delete',     [EmergenciesController::class, 'deleteFile'])->name('emergency.file.delete');

    // ── Calendar (Admin view) ──────────────────────────────────
    Route::get('/calendar',      fn () => view('leads.pg.calendar'))->name('calendar.view');
    Route::get('/calendar/data', [CalendarController::class, 'calendarData'])->name('calendar.data');


    Route::prefix('repair-tickets')->name('repair-tickets.')->group(function () {
        Route::get('/',                                  [RepairTicketController::class, 'index'])       ->name('index');
        Route::post('/',                                 [RepairTicketController::class, 'store'])       ->name('store');
        Route::get('/references',                        [RepairTicketController::class, 'references'])  ->name('references');
        Route::get('/{repairTicket}/edit',               [RepairTicketController::class, 'edit'])        ->name('edit');
        Route::put('/{repairTicket}',                    [RepairTicketController::class, 'update'])      ->name('update');
        Route::delete('/{repairTicket}',                 [RepairTicketController::class, 'destroy'])     ->name('destroy');
        Route::delete('/{repairTicket}/photos/{index}',  [RepairTicketController::class, 'deletePhoto']) ->name('photos.delete');
        Route::post('/{repairTicket}/upload-photos',     [RepairTicketController::class, 'storeAdminPhotos'])->name('upload-photos');
        Route::post('/{repairTicket}/payment',           [RepairTicketController::class, 'savePayment']) ->name('payment'); 
         // ← fix
         
    });

    // ── Chat (Admin) ───────────────────────────────────────────
    Route::get('/chat',                [ChatController::class, 'chatView'])->name('user.chat.view');
    Route::get('/chat/{userId}',       [ChatController::class, 'index'])->name('user.chat.messages');
    Route::post('/chat/send',          [ChatController::class, 'send'])->name('user.chat.send');
    Route::post('/chat/{userId}/read', [ChatController::class, 'markRead'])->name('user.chat.read');
    Route::get('/chat-unread/count',   [ChatController::class, 'unreadCount'])->name('user.chat.unread');

    // ── Team CRUD ──────────────────────────────────────────────
    Route::resource('/teams', TeamController::class);
});




// ============================================================
// SHARED  (auth:web OR auth:team)
// ============================================================

Route::middleware(['auth:web,team'])->group(function () {

    // ── Lead Messages ──────────────────────────────────────────
    Route::get('/leads/{lead_id}/messages', [LeadMessageController::class, 'index'])->name('lead.messages.index');
    Route::post('/leads/messages',          [LeadMessageController::class, 'store'])->name('lead.messages.store');
    Route::delete('/lead-messages/{id}',    [LeadMessageController::class, 'destroy'])->name('lead.messages.destroy');

    // ── Lead Images ────────────────────────────────────────────
    Route::get('/leads/{lead}/images',              [LeadImageController::class, 'index'])->name('lead.images.index');
    Route::post('/leads/images',                    [LeadImageController::class, 'store'])->name('lead.images.store');
    Route::delete('/leads/images/{id}',             [LeadImageController::class, 'destroy'])->name('lead.images.destroy');
    Route::post('/leads/images/bulk-delete',        [LeadImageController::class, 'bulkDelete'])->name('lead.images.bulkDelete');
    Route::delete('/leads/{lead}/images/delete-all',[LeadImageController::class, 'deleteAll'])->name('lead.images.deleteAll');
    Route::post('/leads/images/download-zip',       [LeadImageController::class, 'downloadZip'])->name('lead.images.downloadZip');
    Route::get('/leads/{lead}/images/all-ids',      [LeadImageController::class, 'getAllIds'])->name('lead.images.allIds');

    // ── Lead Files & Folders ───────────────────────────────────
    Route::post('/leads/{lead}/files',       [LeadFilesController::class, 'store'])->name('leads.files.store');
    Route::delete('/leads/files/{leadFile}', [LeadFilesController::class, 'destroy'])->name('leads.files.destroy');
    Route::post('/leads/{lead}/folders',     [LeadFilesController::class, 'storeFolder'])->name('leads.folders.store');
    Route::delete('/leads/folders/{folder}', [LeadFilesController::class, 'destroyFolder'])->name('leads.folders.destroy');
    Route::put('/leads/folders/{folder}',    [LeadFilesController::class, 'update'])->name('leads.folders.update');

    // ── Finanzas & Expenses ────────────────────────────────────
    Route::put('/leads/{lead}/finanzas',               [LeadFinanzaController::class, 'update'])->name('leads.finanzas.update');
    Route::post('/leads/{lead}/finanzas',              [LeadFinanzaController::class, 'store'])->name('lead.finanzas.store');
    Route::delete('/leads/{lead}/finanzas/{finanza}',  [LeadFinanzaController::class, 'destroy'])->name('lead.finanzas.destroy');
    Route::post('/lead-expenses',                      [LeadExpensesController::class, 'store'])->name('lead-expenses.store');
    Route::delete('/lead-expenses/{id}',               [LeadExpensesController::class, 'destroy'])->name('lead-expenses.destroy');

    // ── Quotes ─────────────────────────────────────────────────
    Route::get('/quotes/create',   [QuoteController::class, 'create'])->name('quotes.create');
    Route::post('/quotes',         [QuoteController::class, 'store'])->name('quotes.store');
    Route::delete('/quotes/{quote}',[QuoteController::class, 'destroy'])->name('quotes.destroy');
});




// ============================================================
// ROLE PANELS  (auth:team + team.active)
// ============================================================

// ── Seller ─────────────────────────────────────────────────────────────────
Route::prefix('seller')->middleware('auth:team')->as('seller.')->group(function () {
    Route::get('/dashboard',          [SellerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/leads/{id}',         [SellerDashboardController::class, 'show'])->name('leads.show');
    Route::get('/leads/{id}/edit',    [SellerDashboardController::class, 'edit'])->name('leads.edit');
    Route::get('/create/lead',        [SellerDashboardController::class, 'create'])->name('create');
    Route::post('/create/lead',       [SellerDashboardController::class, 'store'])->name('store');
    Route::match(['put','post'], '/leads/{id}', [SellerDashboardController::class, 'update'])->name('leads.update');
    Route::post('/leads/{id}/update-status', [SellerDashboardController::class, 'updateStatus'])->name('leads.updateStatus');
    // Profile
    Route::get('/profile',           [ProfileTeamController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',           [ProfileTeamController::class, 'update'])->name('profile.update');
    Route::put('/profile/password',  [ProfileTeamController::class, 'updatePassword'])->name('profile.password.update');
});

// ── Guest ──────────────────────────────────────────────────────────────────
Route::prefix('guest')->middleware(['auth:team', 'team.active'])->as('guest.')->group(function () {
    Route::get('/dashboard',              [GuestDashboardController::class, 'index'])->name('dashboard');
    Route::get('/view/guest/{id}',        [GuestDashboardController::class, 'show'])->name('view');
    Route::post('/{lead}/assignstatus',   [GuestDashboardController::class, 'assignStatusManage'])->name('assignstatus');
    // Profile
    Route::get('/profile',           [ProfileTeamController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',           [ProfileTeamController::class, 'update'])->name('profile.update');
    Route::put('/profile/password',  [ProfileTeamController::class, 'updatePassword'])->name('profile.password.update');
});

// ── Manager ────────────────────────────────────────────────────────────────
Route::prefix('manager')->middleware(['auth:team', 'team.active'])->as('manager.')->group(function () {
    Route::get('/dashboard',                        [ManagerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/view/manage/{id}',                 [ManagerDashboardController::class, 'show'])->name('manage');
    Route::post('/manage/{lead}/assignstatus',       [ManagerDashboardController::class, 'assignStatusManage'])->name('assignstatus');
    Route::get('/calendar',                         [ManagerDashboardController::class, 'calendar'])->name('calendar');
    Route::post('/leads/{lead}/assignstatus',        [ManagerDashboardController::class, 'assignStatus'])->name('leads.assignstatus');
    Route::post('/leads/{id}/submit-approved-data', [ManagerDashboardController::class, 'submitApprovedData'])->name('submitApprovedData');
    // Profile
    Route::get('/profile',           [ProfileTeamController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',           [ProfileTeamController::class, 'update'])->name('profile.update');
    Route::put('/profile/password',  [ProfileTeamController::class, 'updatePassword'])->name('profile.password.update');
});

// ── Crew ───────────────────────────────────────────────────────────────────
Route::prefix('crew')->middleware(['auth:team', 'team.active'])->as('crew.')->group(function () {
    Route::get('/dashboard',            [CrewDashboardController::class, 'index'])->name('dashboard');
    Route::get('/calendar',             [CrewDashboardController::class, 'calendar'])->name('calendar');
    Route::get('/leads',                [CrewDashboardController::class, 'index'])->name('leads');
    Route::get('/leads/{id}',           [CrewDashboardController::class, 'show'])->name('view');
    Route::put('/leads/{id}/status',    [CrewDashboardController::class, 'updateLeadStatus'])->name('leads.updateStatus');

    // 📅 Contractor Calendar
    Route::get('/contractor-calendar',  fn () => view('crew.contractor-calendar'))->name('contractor.calendar.view');
    Route::get('/contractor-calendar/data', [CalendarController::class, 'contractorCalendarData'])->name('contractor.calendar.data');

    // Profile
    Route::get('/profile',           [ProfileTeamController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',           [ProfileTeamController::class, 'update'])->name('profile.update');
    Route::put('/profile/password',  [ProfileTeamController::class, 'updatePassword'])->name('profile.password.update');
});

// ── Project Manager ────────────────────────────────────────────────────────
Route::prefix('project')->middleware(['auth:team', 'team.active'])->as('project.')->group(function () {
    Route::get('/dashboard',    [ProjectDashboardController::class, 'index'])->name('dashboard');
    Route::get('/calendar',     [ProjectDashboardController::class, 'calendar'])->name('calendar');
    Route::get('/leads/{id}',   [ProjectDashboardController::class, 'show'])->name('view');
    // Profile
    Route::get('/profile',           [ProfileTeamController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',           [ProfileTeamController::class, 'update'])->name('profile.update');
    Route::put('/profile/password',  [ProfileTeamController::class, 'updatePassword'])->name('profile.password.update');
});

// ── Company Admin ──────────────────────────────────────────────────────────
Route::prefix('admin')->middleware(['auth:team', 'team.active'])->as('admin.')->group(function () {
    Route::get('/dashboard', [CompanyAdminDashboardController::class, 'index'])->name('dashboard');
    // Profile
    Route::get('/profile',           [ProfileTeamController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',           [ProfileTeamController::class, 'update'])->name('profile.update');
    Route::put('/profile/password',  [ProfileTeamController::class, 'updatePassword'])->name('profile.password.update');
});