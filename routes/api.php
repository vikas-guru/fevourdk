<?php

use App\Http\Controllers\Api\ImpactOpsApiController;
use App\Http\Middleware\VerifyUipathToken;
use Illuminate\Support\Facades\Route;

/*
| ImpactOps Maestro — agent API (read-only).
|
| The integration surface UiPath Studio agents read from. Guarded by a bearer
| token (config: services.uipath.token). Writes are NOT exposed here — claim
| decisions, ledger posts and document reviews go through the authenticated app
| routes so the human-in-the-loop and audit trail stay intact.
*/

Route::middleware(VerifyUipathToken::class)->group(function () {
    Route::get('/health', [ImpactOpsApiController::class, 'health']);
    Route::get('/ngo/{ngo}/documents', [ImpactOpsApiController::class, 'documents']);
    Route::get('/ngo/{ngo}/campaigns/{campaign}', [ImpactOpsApiController::class, 'campaign']);
    Route::get('/ngo/{ngo}/finance/claims', [ImpactOpsApiController::class, 'claims']);
    Route::get('/ngo/{ngo}/csr/impact', [ImpactOpsApiController::class, 'csrImpact']);
    Route::get('/field/sessions/{session}/trail', [ImpactOpsApiController::class, 'fieldTrail']);
});
