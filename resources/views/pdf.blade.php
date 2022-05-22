@php
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <meta http-equiv="X-UA-Compatible" content="ie=edge"> --}}
    {{-- <link type="text/css" rel="stylesheet" href="{{ public_path('/css/pdf.css') }}" /> --}}
    {{-- <link type="text/css" rel="stylesheet" href="{{ public_path('/css/app.css') }}" /> --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/pdf.css') }}" /> --}}

    <title>{{ $invoice->invoice_number }} [{{ $invoice->user->name }}]({{ date('d-M-Y') }}).pdf</title>
    <style  data-cfasync="false" >
        @font-face {
            font-family: 'Segoe-UI';
            src: url('{{ public_path('/fonts/segoeui.ttf') }}') format('truetype');
        }

        @font-face {
            font-family: 'Segoe-UI';
            src: url('{{ public_path('/fonts/segoeui.ttf') }}') format('truetype');
            font-weight: 500;
        }

        @font-face {
            font-family: 'Segoe-UI';
            src: url('{{ public_path('/fonts/segoeuil.ttf') }}') format('truetype');
            font-weight: 100;
        }

        @font-face {
            font-family: 'Segoe-UI';
            src: url('{{ public_path('/fonts/segoeuisl.ttf') }}') format('truetype');
            font-weight: 300;
        }

        @font-face {
            font-family: 'Segoe-UI';
            src: url('{{ public_path('/fonts/segoeuib.ttf') }}') format('truetype');
            font-weight: 700;
        }

        @font-face {
            font-family: 'Segoe-UI';
            src: url('{{ public_path('/fonts/segoeuibl.ttf') }}') format('truetype');
            font-weight: 900;
        }

        @font-face {
            font-family: 'Roboto';
            src: url('{{ public_path('/fonts/Roboto-Regular.ttf') }}') format('truetype');
        }

        @font-face {
            font-family: 'Roboto';
            src: url('{{ public_path('/fonts/Roboto-Regular.ttf') }}') format('truetype');
            font-weight: 500;
        }

        @font-face {
            font-family: 'Roboto';
            src: url('{{ public_path('/fonts/Roboto-Bold.ttf') }}') format('truetype');
            font-weight: 700;
        }

        @font-face {
            font-family: 'Roboto';
            src: url('{{ public_path('/fonts/Roboto-Black.ttf') }}') format('truetype');
            font-weight: 900;
        }

        @font-face {
            font-family: 'Roboto';
            src: url('{{ public_path('/fonts/Roboto-Thin.ttf') }}') format('truetype');
            font-weight: 100;
        }

        @font-face {
            font-family: 'Roboto';
            src: url('{{ public_path('/fonts/Roboto-Light.ttf') }}') format('truetype');
            font-weight: 300;
        }

        * {
            font-family: Segoe-UI, Segoe-UI-CH, Roboto, san-serif, Arial;
            /*padding: 0;
            margin: 0;*/
            border-spacing: 0;
            line-height: normal;
        }

        @page {
            /*margin: 100px 50px;*/
            margin: {{ $page->margin->top }} {{ $page->margin->right }} {{ $page->margin->bottom }} {{ $page->margin->left }};
        }


        main {}

        .receipient table {
            width: 100%;
        }

        .cart {}

        .cart table {
            padding: 1rem;
            width: 100%;
            border-spacing: 0;
        }

        .cart thead th {
            font-weight: 300;
            font-size: 0.7rem;
        }

        .cart .items tbody tr:nth-child(2n) {
            background-color: lightgray;
        }

        .cart table tbody tr td {
            padding: 0.2rem;
            font-size: 1rem;

        }

        .cart table tbody tr td:first-of-type {
            text-align: left;
            padding-left: 1rem;
        }

        .cart table tbody tr td:nth-of-type(2) {
            text-align: right;
        }

        .cart table tbody tr td:nth-of-type(3) {
            text-align: center;
        }

        .cart table tbody tr td:last-of-type {
            text-align: right;
            padding-right: 1rem;
        }

        .cart-summary-row {
            background-color: white;
        }

        .cart-summary-label {
            font-size: 1.2rem;
            font-weight: 300;
            text-align: right;
        }

        .cart-summary-value {
            font-size: 1.2rem;
            font-weight: 700;
        }


        .notes {
            margin: 0.5rem;
        }

        .notes-label {
            font-size: 1rem;
            font-weight: 300;
        }



        .notes-area {
            border-left: 0.2rem gray solid;
            margin: 1rem;
            font-size: 1.2rem;
            margin-top: 0.2rem;
            padding: 1rem;
            padding-top: 0.2rem;
        }

        header {
            position: fixed;
		}

        .header-content {
            position: fixed;
            top: {{ $page->offset->top }};
            left: {{ $page->offset->left }};
            right: {{ $page->offset->right }};
            margin: 0.5rem;
        }

        .header-content img {
            left: 0;
            border: 0.4rem lightgray solid;
            width: 100px;
            height: auto;
        }

        .header-content>div {
            right: 0;
            top: 0;
            position: absolute;
            font-size: 1.2rem;
        }

        .header-content div>p {
            text-align: center;
            border: 0.4rem black solid;
            padding: 0.3rem 0.8rem;
            margin: 0;
            margin-left: {{ $page->offset->left }};
            display: inline-block;
            width: max-content;
        }

        .header-content div>p span:first-of-type {
            margin-right: 1rem;
            font-weight: 500;
        }

        .header-content div>p span:last-of-type {
            font-weight: 900;
        }

        .header-content tr td {
            font-weight: 500;
            padding: 0.1rem 0;
            font-size: 1.1rem;
        }

        .header-content tr td:first-of-type {
            padding-left: 1rem;
            padding-right: 0.5rem;
            font-weight: 300;
        }

        .header-content tr td:last-of-type {
            padding-right: 1.2rem;
            font-weight: 700
        }



        footer {
            position: fixed;
            bottom: {{ $page->offset->bottom }};
            left: {{ $page->offset->left }};
            right: {{ $page->offset->right }};
            margin-bottom: 2rem;
            padding-top: 1rem;
            text-align: center;
            font-weight: 300;
        }

        .footer-content {
            margin-left: auto;
            margin-right: auto;
        }

        .footer-content table {
            margin-top: 0.2rem;
            width: 100%;
            font-size: 0.7rem;
        }

        .disclaimer {
			position: absolute;
            bottom:0;
            font-size: 0.7rem;
            font-weight: 300;
			page-break-inside: avoid;
        }

    </style>

</head>

<body>
    <header>
        <div class='header-content'>
            <img src="https://www.element-acoustics.com/wp-content/uploads/2022/05/Element_BigE_Logo.png" />
            <div>
                <p><span>INVOICE</span><span>{{ $invoice->invoice_number }}</span></p>
                <table>
                    <tr>
                        <td>Date</td>
                        <td>{{ \Carbon\Carbon::parse($invoice->created_at)->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td>Sales</td>
                        <td>{{ $invoice->user->name }}</td>
                    </tr>
					<tr>
						<td>Status</td>
						<td>{{ $invoice->status->name }}</td>
					</tr>
                </table>
            </div>
        </div>
    </header>
    <footer>
        <div class="footer-content">
            <img src="https://www.element-acoustics.com/wp-content/uploads/2022/05/Long_Black_Transparent.png"
                width="20%" style="margin:0 auto; padding: 0;" />

            <table>
                <tr>
                    <td style="text-align:right; width: 30%">104 - 13880 Wireless Way, Richmond</td>
                    <td style="text-align:center; width: 3%">|</td>
                    <td style="text-align:center;">www.element-acoustics.com</td>
                    <td style="text-align:center; width: 3%">|</td>
                    <td style="text-align:left; width: 30%">(548) 234-2738</td>
                </tr>
            </table>
        </div>
    </footer>

    <main>
        <div class="receipient">
            <table>
                <tr>
                    <td style="">
						<div>
							<p style="font-size: 0.7rem;">BILL FROM</p>
							<p>{{ $company->name }}</p>
							<p>{{ $company->address1 }}</p>
							<p>{{ $company->address2 ?? '' }}</p>
							<p>{{ $company->city }},  {{ $company->province }}</p>
							<p>{{ $company->country }},  {{ $company->postalcode }}</p>
						</div>
					</td>
                    <td style="">
						<div>
							<p style="font-size: 0.7rem;">BILL TO</p>
							<p>{{ $customer->name }}</p>
							<p>{{ $customer->email.', ' ?? '' }} {{ $customer->phone  ?? ''}}</p>
							<p>{{ $customer->address  ?? '' }}</p>
							<p>{{ $customer->province.', '  ?? '' }} {{ $customer->country  ?? '' }}</p>
						</div>
					</td>
                </tr>
            </table>
        </div>

        <div class="cart" style="">
            <table class="items">
                <thead>
                    <tr>
                        <th style="text-align: left;">Description</th>
                        <th style="text-align: right; width: 10%;">Price</th>
                        <th style="text-align: center; width: 20%;">Quantity</th>
                        <th style="text-align: right; width: 10%;">Total</th>
                    </tr>
                </thead>
                <tbody>
				@foreach ($invoice->invoice_row as $invoice_row)
					<tr>
						<td><p>{{ $invoice_row->description }}</p>
							@if ($invoice_row->discount && $invoice_row->discount !== '$0')
								<p style="font-size: 0.7rem;">Discount</p>
							@endif
							@if ($invoice_row->refund)
								<p style="font-size: 0.7rem;">Refund</p>
							@endif
						</td>
						<td><p>{{ $invoice_row->price }}</p>
							@if ($invoice_row->discount && $invoice_row->discount !== '$0')
								<p style="font-size: 0.7rem;">{{ $invoice_row->discount }}</p>
							@endif
						</td>
						<td><p>{{ $invoice_row->quantity }}</p>
							@if ($invoice_row->refund)
								<p style="font-size: 0.7rem;">{{ $invoice_row->refund }}</p>
							@endif
						</td>
						<td><p>{{ $invoice_row->total }}</p>
							@if ($invoice_row->discount && $invoice_row->discount !== '$0')
								<p style="font-size: 0.7rem;">- {{ $invoice_row->discount_value }}</p>
							@endif
							@if ($invoice_row->refund)
								<p style="font-size: 0.7rem;">- {{ $invoice_row->price * $invoice_row->refund }}</p>
							@endif
						</td>
					</tr>
				@endforeach
				</tbody>
				</table>
				<table>
					<tbody>
				

                    <tr class="cart-summary-row">
                        <td colspan='3' class="cart-summary-label">Gross Total</td>
                        <td class="cart-summary-value">{{  \FormatOutput::dollarFormat($invoice->gross_total) }}</td>
                    </tr>
                    <tr class="cart-summary-row">
                        <td colspan='3' class="cart-summary-label">Shipping & Handling</td>
                        <td class="cart-summary-value">{{ \FormatOutput::moneyFormat($invoice->shipping_handling) ?? '-' }}</td>
                    </tr>
					@if ($invoice->discount !== '$0' || null)
						<tr class="cart-summary-row">
                        <td colspan='3' class="cart-summary-label">Invoice Discount</td>
                        <td class="cart-summary-value">{{ $invoice->discount }}</td>
					@endif
                    </tr>
					@foreach ($taxes as $tax)
						<tr class="cart-summary-row">
							<td colspan='3' class="cart-summary-label">{{ $tax->name }} ({{ $tax->value*100 }}%)</td>
							<td class="cart-summary-value">{{ \FormatOutput::moneyFormat($invoice->before_tax * $tax->value) }}</td>
						</tr>
					@endforeach
                    <tr class="cart-summary-row">
                        <td colspan='3' class="cart-summary-label">NET TOTAL</td>
                        <td class="cart-summary-value">{{ \FormatOutput::dollarFormat($invoice->net_total) }}</td>
                    </tr>

                </tbody>
            </table>
        </div>

        <div class="notes">
            <p class="notes-label">NOTES</p>
            <div class="notes-area">
                {{ $invoice->notes }}
            </div>
        </div>


		<div style="visibility:hidden;">
				<p>{{ $disclaimer->line1 }}</p>
				<p>{{ $disclaimer->line2 }}</p>
				<table>
					<tr>
						<td>Customer Signature:</td>
						<td>______________________________________</td>
					</tr>
				</table>
			</div>
		<div class="disclaimer">
				<p>{!! html_entity_decode($disclaimer->line1) !!}</p>
				<p>{!! html_entity_decode($disclaimer->line2) !!}</p>
				<table>
					<tr>
						<td>Customer Signature:</td>
						<td>______________________________________</td>
					</tr>
				</table>
			</div>


    </main>
</body>

</html>

{{-- <div class="m-3 flex flex-col place-content-between ">
        <div class="flex flex-row justify-start">
            <div class="flex-1 border-r-0 border-red-500">
                <img class="border-8 border-black" src="{{ public_path('/images/E_Short_Logo.png') }}" width="15%" />
            </div>

            <div class="">
                <p class="upperace border-4 border-black p-2 text-right text-2xl font-medium uppercase">Inoivce <span
                        class="font-bold">{{ $invoice->invoice_number }}</span></p>
                <p class="p-2 text-2xl">Date <span class="font-bold">{{ date('d-M-Y') }}</span></p>
                <p class="p-2 text-2xl">Sales <span class="font-bold uppercase">{{ $invoice->salesperson_id }}</span>
                </p>
            </div>
        </div>
        <div class="flex flex-row justify-center m-3">
            <div class="flex flex-col border-r-2 border-black p-2 text-right">
                <p class="font-black uppercase">From</p>
                <p class="font-bold">{{ $company->name }}</p>
                <p class="font-bold">{{ $company->address1 }}{{ ', ' . $company->address2 ?? '' }},
                    {{ $company->city }}</p>
                <p class="font-bold">{{ $company->province }}, {{ $company->country }}{{ $company->postalcode }}</p>
            </div>
            <div class="flex flex-col border-l-2 border-black p-2 text-left">
                <p class="font-black uppercase">To</p>
                <p class="font-bold ">Person Name</p>
                <p class="font-bold">1234 ABCD Street, Richmond</p>
                <p class="font-bold">BC, CA</p>
            </div>
        </div>

        <div class="p-3">
            <table class="w-full table-fixed">
                <thead class="border-b-4 border-gray-600">
                    <tr>
                        <th class="py-2 text-left text-sm font-semibold pl-2">Description</th>
                        <th class="w-1/12 text-right text-sm font-semibold">Price (CAD)</th>
                        <th class="w-3/12 text-center text-sm font-semibold">Quantity</th>
                        <th class="w-1/12 text-right text-sm font-semibold pr-2">Total (CAD)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="even:bg-gray-100">
                        <td class="text-lg text-left inline-block p-2 pl-4">DKoapdk</td>
                        <td class="text-lg text-right">4.23</td>
                        <td class="text-lg text-center">5</td>
                        <td class="text-lg text-right pr-4">20</td>
                    </tr>
                    <tr class="even:bg-gray-100">
                        <td class="text-lg text-left inline-block p-2 pl-4">dsgrsrdg</td>
                        <td class="text-lg text-right">4.23</td>
                        <td class="text-lg text-center">5</td>
                        <td class="text-lg text-right pr-4">20</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex flex-col p-3">
            <p class="font-black uppercase text-lg">Notes</p>
            <div class="border-2 border-black m-2 p-2">
                <p>sdfah sadhfuisadh sahdufisah sahdufah</p>
            </div>
        </div>

        <div class="flex flex-col text-center">
            <img class="m-auto my-2" src="{{ public_path('/images/E_Long_Logo.png') }}" width="20%" />
            <div class="mb-2 flex flex-row">
                <div class="inline-block flex-1">
                    <p class="float-right">{{ $company->address1 }}, Richmond</p>
                </div>

                <div class="flex flex-row">
                    <span class="mx-2 font-bold">|</span>
                    <p class="align-bottom">{{ $company->url ?? '' }}</p>
                    <span class="mx-2 font-bold">|</span>
                </div>

                <div class="inline-block flex-1">
                    <p class="float-left">(548) 234-2738</p>
                </div>
            </div>
        </div>
    </div> --}}

{{-- <div class="m-3 flex flex-col place-content-between ">
  <div class="flex flex-row justify-start">
    <div class="flex-1 border-r-0 border-red-500">
      <img class="border-8 border-black" src="https://www.element-acoustics.com/wp-content/uploads/2022/05/Element_BigE_Logo.png" width="15%" />
    </div>

    <div class="">
      <p class="upperace border-4 border-black p-2 text-right text-2xl font-medium uppercase">Inoivce <span class="font-bold">22005848</span></p>
      <p class="p-2 text-2xl">Date <span class="font-bold">May 02, 2022</span></p>
      <p class="p-2 text-2xl">Sales <span class="font-bold uppercase">Eric</span></p>
    </div>
  </div>
  <div class="flex flex-row justify-center m-3">
    <div class="flex flex-col border-r-2 border-black p-2 text-right">
      <p class="font-black uppercase">From</p>
      <p class="font-bold">Person Name</p>
      <p class="font-bold">1234 ABCD Street, Richmond</p>
      <p class="font-bold">BC, CA</p>
    </div>
    <div class="flex flex-col border-l-2 border-black p-2 text-left">
      <p class="font-black uppercase">To</p>
      <p class="font-bold ">Person Name</p>
      <p class="font-bold">1234 ABCD Street, Richmond</p>
      <p class="font-bold">BC, CA</p>
    </div>
  </div>

  <div class="p-3">
    <table class="w-full table-fixed">
      <thead class="border-b-4 border-gray-600">
        <tr>
          <th class="py-2 text-left text-sm font-semibold pl-2">Description</th>
          <th class="w-1/12 text-right text-sm font-semibold">Price (CAD)</th>
          <th class="w-3/12 text-center text-sm font-semibold">Quantity</th>
          <th class="w-1/12 text-right text-sm font-semibold pr-2">Total (CAD)</th>
        </tr>
      </thead>
      <tbody>
        <tr class="even:bg-gray-100">
          <td class="text-lg text-left inline-block p-2 pl-4">DKoapdk</td>
          <td class="text-lg text-right">4.23</td>
          <td class="text-lg text-center">5</td>
          <td class="text-lg text-right pr-4">20</td>
        </tr>
        <tr class="even:bg-gray-100">
          <td class="text-lg text-left inline-block p-2 pl-4">dsgrsrdg</td>
          <td class="text-lg text-right">4.23</td>
          <td class="text-lg text-center">5</td>
          <td class="text-lg text-right pr-4">20</td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="flex flex-col p-3">
    <p class="font-black uppercase text-lg">Notes</p>
    <div class="border-2 border-black m-2 p-2">
      <p>sdfah sadhfuisadh sahdufisah sahdufah</p>
    </div>
  </div>

  <div class="flex flex-col text-center">
    <img class="m-auto my-2" src="https://www.element-acoustics.com/wp-content/uploads/2022/05/Long_Black_Transparent.png" width="20%" />
    <div class="mb-2 flex flex-row">
      <div class="inline-block flex-1">
        <p class="float-right">34728 Backer Street, Richmond</p>
      </div>

      <div class="flex flex-row">
        <span class="mx-2 font-bold">|</span>
        <p class="align-bottom">www.google.com</p>
        <span class="mx-2 font-bold">|</span>
      </div>

      <div class="inline-block flex-1">
        <p class="float-left">(548) 234-2738</p>
      </div>
    </div>
  </div>
</div> --}}
