<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Customer;
use App\Models\TaxRegion;
use App\Models\Company;
use App\Models\InvoiceRow;
use App\Models\Status;
use App\Models\Tax;
//use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Validator;
use Illuminate\Support\Facades\Hash;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('invoices.index', [
			/* 'invoices'    => Invoice::latest()
				->filter(request(['query', 'salesperson_id', 'company_id', 'customer_id', 'status_id']))
				->get(), */
			'invoices'    => Invoice::latest()->filter()->get(),
			'salespeople' => User::get(['id', 'name']),
			'customers'   => Customer::get(['id', 'name', 'email', 'address', 'phone', 'tax_region']),
			'companies'   => Company::get(['id', 'name']),
			'statuses'    => Status::get(['id', 'name']),
			'hashes' => User::get(['id', 'password', 'is_manager'])->makeVisible(['password']),
		]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
	{
        return view('invoices.create', [
			'companies'   => Company::all(),
			'tax_regions' => TaxRegion::all(),
			'salespeople' => User::all(),
			'customers'   => Customer::all(),
			'statuses' => Status::whereIn('name', ['Draft', 'Completed'])->get(),
			'taxes' => TaxRegion::with('tax')->get()
		]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

		if (!request('status_id')) {
			return redirect()->route('invoices.index');
		}

		//Validate Invoice
		request()->validate(
			[
				'company_id'        => ['required', 'exists:companies,id'],
				'status_id'         => ['required', 'exists:statuses,id'],
				'salesperson_id'    => ['required', 'exists:users,id'],
				'invoice_cart'      => ['required', Rule::notIn(['[]'])],
				'name'              => ['required', 'string'],
				'email'             => ['email', 'nullable'],
				'phone'             => ['string', 'nullable'],
				'address'           => ['string', 'nullable'],
				'province'          => ['string', 'nullable'],
				'country'           => ['string', 'nullable'],
				'tax_region'        => ['required', 'exists:tax_regions,id'],
				'notes'             => ['string', 'nullable'],
				'shipping_handling' => ['numeric', 'nullable'],
				'discount'          => ['string', 'nullable']
			/* 'invoice_cart.*.description'          => ['string', "required"], */
		]);

		//Validate Invoice Row
		$invoice_rows = json_decode($request->invoice_cart, true);

		$validator = Validator::make($invoice_rows, [
			'*.description' => 'required|string',
			'*.price'       => 'required',
			'*.discount'    => 'string|nullable',
			'*.quantity'    => 'required|string'
		]);

		if ($validator->fails()) {
			return redirect()
				->route('invoices.create')
				->withErrors($validator, 'cart')
				->withInput();
		}

		//Create Customer
		$customers = Customer::where(function ($query) {
			$query->where('name', request('name'))
			->where('tax_region', request('tax_region'));
		})->where(function ($query) {
			$query->whenRequest('address')
				->whenRequest('email')
				->whenRequest('phone')
				->whenRequest('province')
				->whenRequest('country');
		})->get();

		$customer = new Customer;
		switch ($customers->count()) {
			case 0: //Make new customer
				$customer->tax_region = request('tax_region');
				$customer->name       = request('name');
				$customer->email      = request('email') ?? null;
				$customer->phone      = request('phone') ?? null;
				$customer->address    = request('address') ?? null;
				$customer->province   = request('province') ?? null;
				$customer->country    = request('country') ?? null;
				$customer->save();
				break;
			case 1:
				$customer = $customers->first();
				break;
			default:
				return redirect()
					->route('invoices.create')
					->withErrors('Customer find fail')
					->withInput();
				break;
		}

		//Create Invoice
		$invoice = new Invoice;

		/* $invoice->customer->firstOrCreate([]); */
		$latest_invoice = Invoice::whereYear('created_at', date("Y"))->get();
		$latest_invoice->fresh();
		if (Invoice::whereYear('created_at', date("Y"))->count() === 0) {
			$invoice->invoice_number = date("y") . str_pad('1', 6, '0', STR_PAD_LEFT);
		} else {
			$invoice->invoice_number = Invoice::latest()->first()->invoice_number + 1;
			while (Invoice::where('invoice_number', $invoice->invoice_number)->exists()) {
				$invoice->invoice_number = $invoice->invoice_number + 1;
			}
		}

		$invoice->customer_id       = $customer->id;
		$invoice->company_id        = request('company_id');
		$invoice->status_id         = request('status_id');
		$invoice->salesperson_id    = request('salesperson_id');
		$invoice->notes             = request('notes');
		$invoice->shipping_handling = request('shipping_handling') ?? 0;
		$invoice->discount          = request('discount') ?? '$0';

		if (Status::find(request('status_id'))->name === 'Completed') {
			$invoice->completed_at = now();
		}
		/* if (Status::find(request('status_id'))->name === 'Paid') {
			$invoice->completed_at = now();
			$invoice->paid_at = now();
		} */
		//dd($invoice);
		$invoice->save();
		$invoice->refresh();

		foreach ($invoice_rows as $invoice_row) {
			$temp_row = new InvoiceRow;

			$temp_row->invoice_id  = $invoice->id;
			$temp_row->description = $invoice_row['description'];
			$temp_row->price       = $invoice_row['price'];
			$temp_row->discount    = $invoice_row['discount'] ?? '$0';
			$temp_row->quantity    = $invoice_row['quantity'];
			$temp_row->save();
		}

		return redirect()
			->route('invoices.show', ['invoice' => $invoice->invoice_number])
			/* ->withHeaders([
			'password' => User::firstWhere('id', request('salesperson_id'))->password,
			'invoice_number' => $invoice->invoice_number ])*/
			->with('created_redirect', true)
			->with('message', 'Invoice Created.');

		/* $invoice = Invoice::create([
			request()->validated()
			'customer_id'->$customer->id
		]); */
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
	public function show(Invoice $invoice)
	{
		$ref = request()->headers->get('referer') ?? false;
		if ((!$ref || !request('password')) && (session('created_redirect') === null)) {
			return redirect()->route('invoices.index')->withMessage(['fail' => 'Not authorized.']);
		}

		$password_hash = User::where('is_manager', '=', true)->get()->makeVisible(['password'])
		->concat([$invoice->user->makeVisible(['password'])])
		->reduce(function ($hash, $item) {
			return $hash || Hash::check(request('password'), $item->password);
		});

		if ($password_hash || session('created_redirect')) {
			return view('invoices.show', ['invoice' => $invoice]);
		} else {
			return redirect()->route('invoices.index');
		}
	}
/*     public function show(Invoice $invoice)
	{
		$ref = request()->headers->get('referer') ?? false;
		if (!$ref || !$invoice || !request('password')) {
			return redirect()->route('invoices.index')->withMessage(['fail' => 'Not authorized.']);
		}

		$db_user     = $invoice->user->makeVisible(['password']);
		$db_managers = User::where('is_manager', '=', true)->get()->makeVisible(['password']);

		$cmp = $db_managers->concat([$db_user])->reduce(function ($hash, $item) {
			return $hash || Hash::check(request('password'), $item->password);
		});

		if ($cmp) {
			return view('invoices.show', ['invoice' => $invoice]);
		} else {
			return redirect()->route('invoices.index');
		}
    } */

    public function show_post(Request $request)
	{
		$ref = request()->headers->get('referer') ?? false;

		if (!$ref || !request('password') || !request('invoice_number')) {
			return redirect()->route('invoices.index')->withMessage(['fail' => 'Not authorized.']);
		}

		$db_user     = Invoice::firstWhere('invoice_number', request('invoice_number'))->user->makeVisible(['password']);
		$db_managers = User::where('is_manager', '=', true)->get()->makeVisible(['password']);

		$cmp = $db_managers->concat([$db_user])->reduce(function ($hash, $item) {
  			return $hash || Hash::check(request('password'), $item->password);
		});

		if ($cmp) {
			$invoice = Invoice::firstWhere('invoice_number', request('invoice_number'));

			return view('invoices.show', [
				'invoice' => $invoice,
				'headers' => request()->headers,
				'request' => request('pass')
			]);
		} else { return redirect()->route('invoices.index'); }
    }

	public function pdf(Invoice $invoice) {
		$t_Company = \App\Models\Company::factory()->make();
		$t_Invoice = \App\Models\Invoice::factory()->has(\App\Models\Company::factory())->make();
		//$data['invoice'] = $t_Invoice;
		//$data['company'] = $t_Company;
		$data['page'] = (object) [
			'margin' => (object) ['left' => '50px', 'right'=> '50px', 'top' => '155px', 'bottom' => '120px'],
			'offset' => (object) ['left' => '-50px', 'right'=> '-50px', 'top' => '-155px', 'bottom' => '-120px']
		];
		$data['disclaimer'] = (object) [
			'line1' => 'Make cheque(s) payable to <span style="font-weight:700;">' . $invoice->company->business_name . '</span>. This bill is to
					be paid upon presentation. Unpaid bills may incur an interest charge of 1.5% per month. <span
						style="font-weight:500;">All sales are final; no refunds. Deposit is non-refundable.</span> Returns
					or exchanges are subject to our sales policy.',
			'line2' => 'By signing below, I (purchaser) have agreed with the sales policy, and confirmed the accuracy of this
					sales invoice.'
		];

		$data['invoice'] = $invoice;
		$data['customer'] = $invoice->customer;
		$data['company'] = $invoice->company;
		$data['taxes'] = $invoice->customer->tax;
		$data['status'] = $invoice->status->name;

		//$pdf = PDF::loadView('reports.today', ['Data' => $Data])->setOptions(['defaultFont' => 'sans-serif']);

		//$pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('pdf', $data);
		//return view('pdf', ['invoice' => $t_Invoice, 'company' => $t_Company]);
		$pdf = PDF::loadView('pdf', $data);
		return $pdf->stream($invoice->invoice_number.'.pdf');
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
