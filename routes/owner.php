<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OwnerController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return response()->view('owner.errors.404', [], 404);
});

Route::view('/', 'owner.login')->name('owner.login');

/*
|--------------------------------------------------------------------------
| OWNER GUEST ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('owner')->middleware('owner.guest')->group(function () {

    Route::view('/register', 'owner.register')->name('owner.register');

    Route::view('/forgot-password', 'owner.forgot-password')
        ->name('owner.forgot-password');

    Route::post('/login', [OwnerController::class, 'authenticate'])
        ->name('owner.auth');

    Route::post('/register', [OwnerController::class, 'registerOwner'])
        ->name('owner.register.submit');
});

/*
|--------------------------------------------------------------------------
| AUTH OWNER ROUTES (NO SUBSCRIPTION REQUIRED)
|--------------------------------------------------------------------------
*/

Route::prefix('owner')->middleware('auth:owner')->group(function () {

    // Subscription Page
    Route::get('/subscription', [OwnerController::class, 'subscription'])->name('owner.subscription');
    Route::get('/subscribe', [OwnerController::class, 'subscribe'])->name('owner.subscribe');

    // Logout
    Route::get('/logout', [OwnerController::class, 'signOut'])
        ->name('owner.signOut');
});

/*
|--------------------------------------------------------------------------
| AUTH OWNER + SUBSCRIPTION REQUIRED
|--------------------------------------------------------------------------
*/

Route::prefix('owner')->middleware(['auth:owner', 'owner.subscription'])->group(function () {

        Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('owner.dashboard');

        // Accountants 
        Route::get('/accountants-list', [OwnerController::class, 'accountants'])->name('owner.accountants.index');
        Route::get('/edit/{id}/accountant', [OwnerController::class, 'editAccountant'])->name('owner.accountants.edit');
        Route::get('/accountant-create', [OwnerController::class, 'createAccountant'])->name('owner.accountants.create');
        Route::post('/accountant-store', [OwnerController::class, 'storeAccountant'])->name('owner.accountants.store');
        Route::post('/accountants/change-status', [OwnerController::class, 'changeStatus'])->name('owner.accountants.changeStatus');
        Route::post('/accountants/{id}',[OwnerController::class, 'updateAccountant'])->name('owner.accountants.updateAccountant');

        
        // Collectors
        Route::get('/collectors-list', [OwnerController::class, 'collectors'])->name('owner.collectors.index');
        Route::get('/edit/{id}/collectors', [OwnerController::class, 'editCollector'])->name('owner.collectors.edit');
        Route::get('/collectors-create', [OwnerController::class, 'createCollector'])->name('owner.collectors.create');
        Route::post('/collectors-store', [OwnerController::class, 'storeCollector'])->name('owner.collectors.store');
        Route::post('/collectors/change-status', [OwnerController::class, 'changeStatusCollector'])->name('owner.collectors.changeStatus');
        Route::post('/collectors/{id}',[OwnerController::class, 'updateCollector'])->name('owner.collectors.update');



        // Tally Dashboard
        Route::get('/tally/dashboard', [OwnerController::class, 'company'])->name('owner.tally.dashboard');

        // Sync all data from tally prime
        Route::get('/tally/sync-all', [OwnerController::class, 'syncAll'])->name('owner.tally.sync-all');

        Route::get('/tally/company/{company}',[OwnerController::class, 'companyDetails'])->name('owner.tally.company.details');

        Route::get(
            '/tally/company/{company}/ledgers',
            [OwnerController::class, 'companyLedgers']
        )->name('owner.tally.company.ledgers');


        Route::get(
            '/tally/company/{company}/ledger/{ledger}/invoices',
            [OwnerController::class, 'ledgerInvoices']
        )->name('owner.tally.ledger.invoices');

        Route::get(
            '/tally/company/{company}/ledger/{ledger}/receipts',
            [OwnerController::class, 'ledgerReceipts']
        )->name('owner.tally.ledger.receipts');


        Route::get(
            '/tally/company/{company}/ledger/{ledger}/vouchers/{under?}',
            [OwnerController::class, 'ledgerVouchers']
        )->name('owner.tally.ledger.vouchers');

        Route::get(
            '/tally/voucher-mappings/{company}',
            [OwnerController::class, 'voucherMappings']
        )->name('owner.tally.voucher.mappings');


        Route::post('/voucher-mappings/save', [OwnerController::class, 'saveVoucherMappings'])->name('owner.voucher-mappings.save');

            
        // Ledgers
        Route::get('/ledgers-list', [OwnerController::class, 'ledgers'])->name('owner.ledgers.index');

    });