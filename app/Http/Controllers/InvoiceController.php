<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Customer;
use App\Models\TaxRegion;
use App\Models\Company;
use App\Models\Status;


use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
//use Validator;
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
			'statuses' => Status::all()
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
		request()->validate([
			'company'    => ['required', 'exists:companies,id'],
			'status'     => ['required', 'exists:statuses,id'],
			'user'       => ['required', 'exists:users,id'],
			'payload'    => ['required', Rule::notIn(['[]'])],
			'name'       => ['required', 'string'],
			'address'    => ['string'],
			'email'      => ['email'],
			'phone'      => ['string'],
			'country'    => ['string'],
			'province'   => ['string'],
			'tax_region' => ['required', 'exists:tax_regions,id'],
			'notes'      => ['string']
		]);

		//Validate Invoice Row
		$data = json_decode($request->payload, true);
		$validator = Validator::make($data , [
			'description' => 'required|string',
			'price'       => 'required|digits',
			'discount'    => 'string|nullable',
			'quantity'    => 'required|digits'
		]);

		if ($validator->fails()) {
			return redirect()
				->route('invoices.create')
				->withErrors($validator, 'cart')
				->withInput();
        }
		
		
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
