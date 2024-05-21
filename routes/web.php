<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\ProvisionesController;
use App\Http\Controllers\AsistenteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\UsersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/inicio', [InicioController::class, 'index'])->name('inicio');


    // CertifcadoRetencion
    Route::get('/documentos/certificado-retenciones', [PortalController::class, 'certificadoRetencion'])->name('documentos.certificado-retenciones');
    Route::get('/documentos/certificado-retenciones/pdf', [PortalController::class, 'certificadoRetencionPdf'])->name('documentos.certificado-retenciones-pdf');

    // documentos.facturas-registrada
    Route::get('/documentos/facturas-registradas', [PortalController::class, 'facturasRegistradas'])->name('documentos.facturas-registradas');
    Route::get('/documentos/facturas-registradas/json', [PortalController::class, 'facturasRegistradasJson'])->name('documentos.facturas-registradas-json');


    // documentos  pagos efectuados
    Route::get('/documentos/pagos-efectuados', [PortalController::class, 'pagosEfectuados'])->name('documentos.pagos-efectuados');
    Route::get('/documentos/pagos-efectuados/json', [PortalController::class, 'pagosEfectuadosJson'])->name('documentos.pagos-efectuados-json');

    // documentos Preliquidaciones
    Route::get('/documentos/preliquidaciones', [PortalController::class, 'preliquidaciones'])->name('documentos.preliquidaciones');
    Route::get('/documentos/preliquidaciones/json', [PortalController::class, 'preliquidacionesJson'])->name('documentos.preliquidaciones-json');

    // logs
    Route::get('/logs', [LogsController::class, 'index'])->name('logs');
    Route::get('/logs/{log}', [LogsController::class, 'details'])->name('logs.details');

    // users
    Route::get('/usuarios', [UsersController::class, 'index'])->name('users.index');
    Route::get('/usuarios/json', [UsersController::class, 'getUserJson'])->name('users.json');
    Route::get('/usuarios/impersonate', [UsersController::class, 'impersonateUser'])->name('users.impersonate');

    // Impersonate
    Route::impersonate();

});

Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
