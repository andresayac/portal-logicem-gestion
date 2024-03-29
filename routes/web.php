<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\ProvisionesController;
use App\Http\Controllers\AsistenteController;
use Illuminate\Support\Facades\Route;

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

    Route::get('/provisiones', [ProvisionesController::class, 'index'])->name('provisiones');
    Route::get('/provisiones/log', [ProvisionesController::class, 'log'])->name('provisiones.log');
    Route::get('/provisiones/log/{log}', [ProvisionesController::class, 'logDetails'])->name('provisiones.log.details');
    Route::get('/provisiones/log/{log}/remesas', [ProvisionesController::class, 'logRemesas'])->name('provisiones.log.remesas');
    Route::get('/provisiones/clientes', [ProvisionesController::class, 'getClientes'])->name('provisiones.clientes');
    Route::get('/provisiones/getProvisiones', [ProvisionesController::class, 'getProvisiones'])->name('provisiones.getProvisiones');
    Route::post('/provisiones/sendProvisiones', [ProvisionesController::class, 'sendProvisiones'])->name('provisiones.sendProvisiones');

    Route::get('/asistente', [AsistenteController::class, 'index'])->name('asistente');
    Route::get('/asistente/proveedores', [AsistenteController::class, 'proveedores'])->name('asistente.proveedores');
    Route::get('/asistente/manifiestos', [AsistenteController::class, 'manifiestos'])->name('asistente.manifiestos');
    Route::post('/asistente/sendManifiestos', [AsistenteController::class, 'sendManifiestos'])->name('asistente.sendManifiestos');
    Route::get('/asistente/log', [AsistenteController::class, 'log'])->name('asistente.log');
    Route::get('/asistente/log/{log}', [AsistenteController::class, 'logDetails'])->name('asistente.log.details');
});

Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
