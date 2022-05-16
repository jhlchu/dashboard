<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tax;

class TaxController extends Controller
{
	public function APIShow(Request $request) {
		$validator = \Validator::make($request->all(), [
			'id' => ['required', 'exists:taxes,id']
		]);
		if ($validator->fails()) {
			return response(['message' => $validator->messages()], 400);
		} else {
			return Tax::where('region_id', request('id'))->get();
		}


	}
}
