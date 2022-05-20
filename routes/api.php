<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\CustomerResource;
//use App\Http\Controllers\TaxController;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/customers', function (Request $request) {
	$validator = Validator::make($request->all(), ['name' => ['required', 'string']]);
	if ($validator->fails()) {
		return response(['message' => $validator->messages()], 400);
	} else {
		try {
			return CustomerResource::collection(Customer::where('name', 'like', '%' . request('name') . '%')->limit(5)->get());
		} catch (\Exception $e) {
			return response(['message' => $e->getMessage()], 400);
		}
	}
});

Route::post('/customers/{customer}', function (Customer $customer) {
	return new CustomerResource($customer);
});

Route::post('/emails', function () {
	return new CustomerResource(
		Customer::where('email', 'like', '%' . request('email') . '%')->pluck('email')->unique()
	);
});
Route::post('/phone_numbers', function () {
	return new CustomerResource(
		Customer::where('phone', 'like', '%' . request('phone') . '%')->pluck('phone')->unique()
	);
});
Route::post('/addresses', function () {
	return new CustomerResource(
		Customer::where('address', 'like', '%' . request('address') . '%')->pluck('address')->unique()
	);
});
Route::post('/provinces', function () {
	return new CustomerResource(
		Customer::where('province', 'like', '%' . request('province') . '%')->pluck('province')->unique()
	);
});
Route::post('/countries', function () {
	return new CustomerResource(
		Customer::where('country', 'like', '%' . request('country') . '%')->pluck('country')->unique()
	);
});
/* Route::post('/taxes', [TaxController::class, 'APIShow']); */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
