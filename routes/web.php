<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\AVPController;
use App\Http\Controllers\VPController;
use App\Http\Controllers\SVPController;
use App\Http\Controllers\DKUController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

// Auth
Route::post('/login', [AuthController::class, "login"])->name('signin');
Route::post('/logout', [AuthController::class, "logout"])->name('signout');

// Dashboard
Route::get('/home', [DashboardController::class, 'index'])->name('dashboard');

// Buyer Contract
Route::get('/buyer/contract/monitoring', [BuyerController::class, 'contracts_monitoring'])->name('buyer.contracts-monitoring');
Route::get('/buyer/contract/monitoring/create', [BuyerController::class, 'contract_monitoring_create'])->name('buyer.contract-monitoring-create');
Route::post('/buyer/contract/monitoring/store', [BuyerController::class, 'contract_monitoring_store'])->name('buyer.contract-monitoring-store');
Route::get('/buyer/contract/{contract}/monitoring', [BuyerController::class, 'contract_monitoring'])->name('buyer.contract-monitoring');
Route::get('/buyer/contract/{contract}/vendor/{vendor}', [BuyerController::class, 'contract_detail'])->name('buyer.contract-detail');
Route::get('/buyer/contract/{contract}/vendor/{vendor}/edit', [BuyerController::class, 'contract_edit'])->name('buyer.contract-edit');
Route::put('/buyer/contract/{contract}/vendor/{vendor}/update', [BuyerController::class, 'contract_update'])->name('buyer.contract-update');

Route::get('/buyer/contract/review-vendor', [BuyerController::class, 'contracts_review_vendor'])->name('buyer.contracts-review-vendor');
Route::get('/buyer/contract/{contract}/vendor/{vendor}/review-vendor', [BuyerController::class, 'contract_review_vendor'])->name('buyer.contract-review-vendor');
Route::post('/buyer/contract/{contract}/vendor/{vendor}/review-vendor/return', [BuyerController::class, 'contract_review_vendor_return'])->name('buyer.contract-review-vendor-return');
Route::post('/buyer/contract/{contract}/vendor/{vendor}/review-vendor/review', [BuyerController::class, 'contract_review_vendor_review'])->name('buyer.contract-review-vendor-review');
Route::post('/buyer/contract/{contract}/vendor/{vendor}/avp', [BuyerController::class, 'contract_vendor_avp'])->name('buyer.contract-vendor-avp');

Route::get('/buyer/contract/review-legal', [BuyerController::class, 'contracts_review_legal'])->name('buyer.contracts-review-legal');
Route::get('/buyer/contract/{contract}/vendor/{vendor}/review-legal', [BuyerController::class, 'contract_review_legal'])->name('buyer.contract-review-legal');

Route::get('/buyer/contract/approval', [BuyerController::class, 'contracts_approval'])->name('buyer.contracts-approval');
Route::get('/buyer/contract/{contract}/vendor/{vendor}/approval', [BuyerController::class, 'contract_approval'])->name('buyer.contract-approval');
Route::post('/buyer/contract/{contract}/vendor/{vendor}/send', [BuyerController::class, 'contract_send'])->name('buyer.contract-send');

Route::get('/buyer/contract/final', [BuyerController::class, 'contracts_final'])->name('buyer.contracts-final');
Route::get('/buyer/contract/{contract}/vendor/{vendor}/final', [BuyerController::class, 'contract_final'])->name('buyer.contract-final');

// Vendor Contract
Route::get('/vendor/contract', [VendorController::class, 'contracts'])->name('vendor.contracts');
Route::get('/vendor/contract/{contract}/vendor/{vendor}', [VendorController::class, 'contract'])->name('vendor.contract');
Route::get('/vendor/contract/{contract}/vendor/{vendor}/edit', [VendorController::class, 'contract_edit'])->name('vendor.contract-edit');
Route::put('/vendor/contract/{contract}/vendor/{vendor}/update', [VendorController::class, 'contract_update'])->name('vendor.contract-update');

//Legal Contract
Route::get('/legal/contract', [LegalController::class, 'contracts'])->name('legal.contracts');
Route::get('/legal/contract/{contract}/vendor/{vendor}', [LegalController::class, 'contract'])->name('legal.contract');
Route::post('/legal/contract/{contract}/vendor/{vendor}/approval', [LegalController::class, 'contract_approval'])->name('legal.contract-approval');

// AVP
Route::get('/avp/contract', [AVPController::class, 'contracts'])->name('avp.contracts');
Route::get('/avp/contract/{contract}/vendor/{vendor}', [AVPController::class, 'contract'])->name('avp.contract');
Route::get('/avp/contract/review', [AVPController::class, 'review_contracts'])->name('avp.review-contracts');
Route::get('/avp/contract/{contract}/vendor/{vendor}/review', [AVPController::class, 'review_contract'])->name('avp.review-contract');
Route::post('/avp/contract/{contract}/vendor/{vendor}/return', [AVPController::class, 'contract_return'])->name('avp.contract-return');
Route::post('/avp/contract/{contract}/vendor/{vendor}/approval', [AVPController::class, 'contract_approval'])->name('avp.contract-approval');

//VP
Route::get('/vp/contract', [VPController::class, 'contracts'])->name('vp.contracts');
Route::get('/vp/contract/{contract}/vendor/{vendor}', [VPController::class, 'contract'])->name('vp.contract');
Route::get('/vp/contract/review', [VPController::class, 'review_contracts'])->name('vp.review-contracts');
Route::get('/vp/contract/{contract}/vendor/{vendor}/review', [VPController::class, 'review_contract'])->name('vp.review-contract');
Route::post('/vp/contract/{contract}/vendor/{vendor}/return', [VPController::class, 'contract_return'])->name('vp.contract-return');
Route::post('/vp/contract/{contract}/vendor/{vendor}/approval', [VPController::class, 'contract_approval'])->name('vp.contract-approval');

//SVP
Route::get('/svp/contract', [SVPController::class, 'contracts'])->name('svp.contracts');
Route::get('/svp/contract/{contract}/vendor/{vendor}', [SVPController::class, 'contract'])->name('svp.contract');
Route::get('/svp/contract/review', [SVPController::class, 'review_contracts'])->name('svp.review-contracts');
Route::get('/svp/contract/{contract}/vendor/{vendor}/review', [SVPController::class, 'review_contract'])->name('svp.review-contract');
Route::post('/svp/contract/{contract}/vendor/{vendor}/return', [SVPController::class, 'contract_return'])->name('svp.contract-return');
Route::post('/svp/contract/{contract}/vendor/{vendor}/approval', [SVPController::class, 'contract_approval'])->name('svp.contract-approval');

//DKU
Route::get('/dku/contract', [DKUController::class, 'contracts'])->name('dku.contracts');
Route::get('/dku/contract/{contract}/vendor/{vendor}', [DKUController::class, 'contract'])->name('dku.contract');
Route::get('/dku/contract/review', [DKUController::class, 'review_contracts'])->name('dku.review-contracts');
Route::get('/dku/contract/{contract}/vendor/{vendor}/review', [DKUController::class, 'review_contract'])->name('dku.review-contract');
Route::post('/dku/contract/{contract}/vendor/{vendor}/return', [DKUController::class, 'contract_return'])->name('dku.contract-return');
Route::post('/dku/contract/{contract}/vendor/{vendor}/approval', [DKUController::class, 'contract_approval'])->name('dku.contract-approval');

//Final Approval
Route::post('/contract/{contract}/vendor/{vendor}/final-approval', [DKUController::class, 'final_approval'])->name('final-approval');

// backup
Route::post('/contract/detail/{contract}/vendor/{vendor}/review', [ContractController::class, 'reviewLegal'])->name('contract.buyer-reviewLegal');
Route::get('/contract/{contract}/legal/edit', [ContractController::class, 'editLegal'])->name('contract.legal-edit');
