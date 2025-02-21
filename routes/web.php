<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TicketController;
use App\Models\Ticket;

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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->role === 'agent') {
            return redirect()->route('agent.dashboard');
        }
        return redirect()->route('client.dashboard');
    })->name('dashboard');



    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::post('/admin/addCategory', [CategoryController::class, 'store'])->name('admin.addCategory');
        Route::put('/admin/modifyCategory', [CategoryController::class, 'update'])->name('admin.modifyCategory');
        Route::delete('/admin/deleteCategories/{id}', [CategoryController::class, 'destroy'])->name('admin.destroyCategory');
        Route::put('admin/tickets/{currentTicketId}/assign-category', [TicketController::class, 'assignCategory'])->name('tickets.assignCategory');
        Route::put('admin/tickets/{currentTicketId}/assign', [TicketController::class, 'assignAgent'])->name('tickets.assignAgent');
        Route::put('/admin/tickets/{currentTicketId}/status', [TicketController::class, 'changeStatus'])->name('tickets.changeStatus');
        Route::get('/admin/tickets', [TicketController::class, 'index'])->name('admin.tickets');
        Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories');

    });

    Route::middleware(['role:agent'])->group(function () {
        Route::get('/agent/dashboard', [AgentController::class, 'index'])->name('agent.dashboard');
    });

    Route::middleware(['role:client'])->group(function () {
        Route::get('/client/dashboard', [ClientController::class, 'index'])->name('client.dashboard');
        Route::get('/client/tickets', [TicketController::class, 'clientTickets'])->name('client.tickets');
        Route::post('/client/addTicket', [TicketController::class, 'store'])->name('tickets.store');
        Route::put('/client/updateTicket', [TicketController::class, 'update'])->name('tickets.update');
        Route::put('/client/closeTicket', [TicketController::class, 'close'])->name('client.closeTicket');

    });
});


require __DIR__.'/auth.php';
