<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FallbackController;
use App\Http\Controllers\SettingsController;

Route::get('/', fn() => view('index',[]) );
Route::get('/calculator', fn () => view('calculator'));
Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

/* Route::post('/invoices/{invoice}', [InvoiceController::class, 'show_post'])->name('invoices.show_post'); */
Route::post('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show.post');
Route::post('/invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');
Route::resources(['invoices'  => InvoiceController::class,
	'customers' => CustomerController::class,
	'users'     => UserController::class,
	'companies' => CompanyController::class,
	'contracts' => ContractController::class
]);


//Route::controller(InvoiceController::class)->prefix('invoice')->name('invoice.')->group(function () {
/* 	Route::get ('/'          , 'index'  )->name('index'  );
	Route::get ('/create'    , 'create' )->name('create' );
	Route::post ('/store'    , 'store' )->name('store' );
	Route::get ('/{invoice}' , 'show'   )->name('show'   );
	Route::post('/{invoice}' , 'show'   )->name('show'   );
	//Route::match(['get', 'post'], '/{id}')->name('show');
	Route::get ('/{id}/edit' , 'edit'   )->name('edit'   );
	Route::get('{all}', 'index')->name('fallback'); */
//});

//Route::get('/invoice/i/{id}', function ($id) {
	//return redirect()->route('invoice.show', ['id' => $id])->with('POOPOO', 'PEEPEE');
	//return redirect('invoice.show', 300)->route('invoice.show', ['id' => $id])->with('POOPOO', 'PEEPEE');
//});

//Route::fallback(FallbackController::class);