<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaxRegion;
use App\Models\Tax;
use App\Models\Status;
use App\Models\Company;

class SettingsController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('settings.index', [
			'tax_regions' => TaxRegion::all(),
			'taxes' => Tax::all(),
			'statuses' => Status::all(),
			'companies' => Company::all()
		]);
    }
}
