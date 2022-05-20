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
			'invoices'    => Invoice::latest()
				->filter(request(['query', 'salesperson_id', 'company_id', 'customer_id', 'status_id']))
				->get(),
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
			'statuses' => Status::whereIn('name', ['Draft', 'Completed', 'Paid'])->get(),
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

		if (request('status') === 'cancel') {
			return redirect()->route('invoices.index');
		}

		//Validate Invoice
		request()->validate(['company_id'        => ['required', 'exists:companies,id'],
			'status'            => ['required', 'exists:statuses,id'],
			'salesperson_id'    => ['required', 'exists:users,id'],
			'invoice_cart'      => ['required', Rule::notIn(['[]'])],
			'name'              => ['required', 'string'],
			'email'             => ['email', "nullable"],
			'phone'             => ['string', "nullable"],
			'address'           => ['string', "nullable"],
			'province'          => ['string', "nullable"],
			'country'           => ['string', "nullable"],
			'tax_region'        => ['required', 'exists:tax_regions,id'],
			'notes'             => ['string', "nullable"],
			'shipping_handling' => ['digit', "nullable"],
			'discount'          => ['string', "nullable"]
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

		dd(request());

		//Create Customer
		$customers = Customer::where(function ($query) {
			$query->where('name', request('name'))
			->where('tax_region', request('tax_region'));
		})->where(
			function ($query) {
			$query->where('address', request('address'))
			->orWhere('email', request('email'))
					->orWhere('phone', request('phone'));
				/* ->when( request('province', function ($query) {
						$query->where();
					})
					->orWhere('province', request('province'))
					->orWhere('country', request('country'));
				) */
		})->get();

		switch ($customers->count()) {
			case 0:
				//Make new customer
				$customer = new Customer;
				$customer->address = request('address');
				$customer->email = request('email');
				$customer->phone = request('phone');
				$customer->province = request('province');
				$customer->country = request('country');
				$customer->store();
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
		$invoice->customer_id;

		if (Invoice::whereYear('created_at', date("Y"))->count() === 0) {
			$invoice->invoice_number = date("y") . str_pad(1, 6, '0', STR_PAD_LEFT); //New Year
		} else {
			$invoice->invoice_number = intval(Invoice::latest()->first()->value('invoice_number')) + 1; //Same Year
		}

		$invoice_grosstotal = collect($invoice_rows)->reduce(function ($total, $item) {
			[$price, $discount, $quantity] = $item;
			if ($discount . contains('%')) {
				return $total + (($price * (1 - floatval($discount) / 100)) * $quantity);
			}
			if ($discount . contains('$')) {
				return $total + (($price - floatval($discount)) * $quantity);
			}
			return $total + ($price * $quantity);
		}, 0);

		if (request('discount') . contains('%')) {
			$invoice_discount = $invoice_grosstotal * (floatval(request('discount')) / 100);
		}
		if (request('discount') . contains('$')) {
			$invoice_discount = floatval(request('discount'));
		}

		$invoice_tax = Tax::where('region_id', request('tax_region'))->get()->reduce(function ($tax_total, $tax) {
			return $tax_total + $tax->value;
		}, 0);

		$invoice_nettotal = ($invoice_grosstotal + request('shipping_handling') - $invoice_discount) * (1 + $invoice_tax);

		$invoice->company_id = request('company_id');
		$invoice->status = request('status');
		$invoice->salesperson_id = request('salesperson_id');
		$invoice->notes = request('notes');
		$invoice->shipping_handling = request('shipping_handling');
		$invoice->discount_string = request('discount_string');
		$invoice->net_total = $invoice_nettotal;

		if (Status::find(request('status'))->name === 'Completed') {
			$invoice->completed_at = request('completed_at');
		}
		if (Status::find(request('status'))->name === 'Paid') {
			$invoice->completed_at = now();
			$invoice->paid_at = now();
		}
		$invoice->store();

		foreach ($invoice_rows as $invoice_row) {
			[$description, $price, $discount, $quantity] = $invoice_row;

			$temp_row = new InvoiceRow;

			$temp_row->invoice_id  = $invoice->invoice_number;
			$temp_row->description = $description;
			$temp_row->price       = $price;
			$temp_row->discount    = $discount;
			$temp_row->quantity    = $quantity;
			$temp_row->store();
		}

		/* $invoice = Invoice::create([
			request()->validated()
			'customer_id'->$customer->id
		]); */

	
		//Create Invoice Rows
		/* foreach ($data as $invoice_row) {
			InvoiceRow::create([
				'invoice_id' => request()->id,
				'description' => ,
				'price' => ,
				'quantity' => ,
				'discount_string' => ,
				'discount_value' => ,
				'refund_quantity' => ,
				'deleted' => 
			])
		}  */



		/* 
			$input = [
				'user' => [
					'name' => 'Taylor Otwell',
					'username' => 'taylorotwell',
					'admin' => true,
				],
			];

			Validator::make($input, [
				'user' => 'array:username,locale',
			]);
		*/

        //dd(request()->all());
		/* $invoice = Invoice::create([
			'invoice_number' => date("y").str_pad(392, 6, '0', STR_PAD_LEFT),
			'company_id' => request()->company_id,
			'status_id' => 1,
			'salesperson_id' => request()->salesperson_id,
			'customer_id' => request()->customer_id,
			'completed_at' => now(),
			'paid_at' => now()
		]);

		dd($invoice);
 */
		//foreach ($invoice_cart as $invoice_row) {
/* 			Invoice_Row::create([
				''
			]);
			dump($invoice_row);
			$table->increments('id');
			$table->unsignedBigInteger('invoice_id');
			$table->tinyText('description');
			$table->unsignedFloat('price');
			$table->unsignedTinyInteger('quantity');
			$table->string('discount_string')->nullable();
			$table->unsignedFloat('discount_value')->nullable();
			$table->unsignedTinyInteger('refund_quantity')->nullable();
			$table->boolean('deleted'); */
			//$table->timestamps();
		//}
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
		if ($ref) {
			return view('invoices.show', [
				'invoice' => $invoice,
				'headers' => request()->headers,
				'request' => request('pass')
			]);
		} else { return redirect()->route('invoices.index'); }
    }

    public function show_post(Request $request)
    {
		$invoice_number = request('invoice_number');

		$ref = request()->headers->get('referer') ?? false;

		if (!$ref || !request('password') || !$invoice_number) {
			return redirect()->route('invoices.index');
		}

		$db_user = Invoice::where('invoice_number', request('invoice_number'))->first()->user->makeVisible(['password']);
		$db_managers = User::where('is_manager', '=', true)->get()->makeVisible(['password']);

		$cmp = $db_managers->concat([$db_user])->reduce(function ($hash, $item) {
  			return $hash || Hash::check(request('password'), $item->password);
		});


		if ($cmp) {
			return view('invoices.show', [
				'invoice' => Invoice::where('invoice_number', request('invoice_number'))->first(),
				'headers' => request()->headers,
				'request' => request('pass')
			]);
		} else { return redirect()->route('invoices.index'); }
    }

	public function pdf(Invoice $invoice) {
		$t_Company = \App\Models\Company::factory()->make();
		$t_Invoice = \App\Models\Invoice::factory()->has(\App\Models\Company::factory())->make();
		$data['invoice'] = $t_Invoice;
		$data['company'] = $t_Company;
		$data['page'] = (object) [
			'margin' => (object) ['left' => '50px', 'right'=> '50px', 'top' => '155px', 'bottom' => '120px'],
			'offset' => (object) ['left' => '-50px', 'right'=> '-50px', 'top' => '-155px', 'bottom' => '-120px']
		];
		$data['disclaimer'] = (object) [
			'line1' => 'Make cheque(s) payable to <span style="font-weight:700;">Element Acoustics Inc.</span> This bill is to
					be paid upon presentation. Unpaid bills may incur an interest charge of 1.5% per month. <span
						style="font-weight:500;">All sales are final; no refunds. Deposit is non-refundable.</span> Returns
					or exchanges are subject to our sales policy.',
			'line2' => 'By signing below, I (purchaser) have agreed with the sales policy, and confirmed the accuracy of this
					sales invoice.'
		];


		$customer = $invoice->customer;
		$company = $invoice->company->address;
		$taxes = $invoice->customer->tax;
		$status = $invoice->status->name;
		$invoice_rows = $invoice->invoice_rows;
		//dd($o);
		//dd($t_Invoice->toArray());


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
