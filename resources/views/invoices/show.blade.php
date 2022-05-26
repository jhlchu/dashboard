@php
	//dd($invoice->user->name)
	//$headers
@endphp
@extends('layout')
@section('content')

<div class="flex flex-row justify-around p-3 md:justify-center">


	<form action="{{ route('invoices.pdf', ['invoice' => $invoice]) }}" method="POST">
		@csrf
		<button class="m-1 mx-1 flex-grow basis-1/3 rounded border-2 py-1 px-3 text-base font-medium text-gray-700 hover:bg-yellow-300 hover:text-white md:flex-grow-0 md:basis-1/5">PDF</button>
	</form>
	@if ($invoice->status->name === 'Deleted')
		<button class="m-1 mx-1 flex-grow basis-1/3 rounded border-2 py-1 px-3 text-base font-medium text-gray-700 hover:bg-blue-500 hover:text-white md:flex-grow-0 md:basis-1/5">Restore</button>
	@else
		<button class="m-1 mx-1 flex-grow basis-1/3 rounded border-2 py-1 px-3 text-base font-medium text-gray-700 hover:bg-blue-500 hover:text-white md:flex-grow-0 md:basis-1/5">Edit</button>
		<button class="m-1 mx-1 flex-grow basis-1/3 rounded border-2 py-1 px-3 text-base font-medium text-gray-700 hover:bg-red-500 hover:text-white md:flex-grow-0 md:basis-1/5">Delete</button>
	@endif
</div>
<div class="m-auto mt-[4.5rem] flex flex-col max-w-[80%]">
	{{-- <p>Request Pass: {{ $request }}</p> --}}
	<div class="flex justify-between flex-row">
			<div class="flex flex-col">
				<h1 class="text-4xl"><span class="font-medium">Invoice</span> {{ $invoice->invoice_number }}</h1>
				<h2 class="text-3xl"><x-status-badge :status="$invoice->status"/></h2>
			</div>
			<div class="flex flex-col justify-end">
				<h2 class="text-sm text-right">Salesperson</h2>
				<p class="text-lg text-right">{{ $invoice->user->name }}</p>
			</div>
		
	</div>
  
	<div class="flex flex-col justify-evenly md:flex-row mt-4">
		<div class="my-1 md:my-3">
			<div class="border-b-2 text-center font-bold">Date Created</div>
			<div class="text-center">{{ FormatOutput::dateTimeFormat($invoice->created_at) }}</div>
		</div>
		<div class="my-1 md:my-3 min-width-[140]">
			<div class="border-b-2 text-center font-bold">Date Completed</div>
			<div class="text-center">{{ $invoice->completed_at ? FormatOutput::dateTimeFormat($invoice->completed_at) : '-' }}</div>
		</div>
{{-- 		<div class="my-1 md:my-3">
			<div class="border-b-2 text-center font-bold">Date Paid</div>
			<div class="text-center">{{ $invoice->paid_at ? FormatOutput::dateTimeFormat($invoice->paid_at) : '-' }}</div>
		</div> --}}
		<div class="my-1 md:my-3">
			<div class="border-b-2 text-center font-bold">Date Updated</div>
			<div class="text-center">{{ FormatOutput::dateTimeFormat($invoice->updated_at) }}</div>
		</div>
	</div>
  

	<div class="mt-3 mb-2 flex flex-col justify-evenly md:my-3 md:flex-row">
		<div class="flex-grow text-center">
			<p class="border-b-2 text-center font-bold">Name</p>
			<p><a href="{{ route('customers.show', ['customer' => $invoice->customer->id]) }}">{{ $invoice->customer->name }}</a></p>
		</div>
		<div class="flex-grow text-center">
			<p class="border-b-2 text-center font-bold">Email</p>
			<p><a href='email:{{ $invoice->customer->email }}'>{{ $invoice->customer->email ?? '-' }}</a></p>
		</div>
		<div class="flex-grow text-center">
			<p class="border-b-2 text-center font-bold">Phone</p>
			<p><a href='tel:{{ $invoice->customer->phone }}'>{{ $invoice->customer->phone ?? '-' }}</a></p>
		</div>
		<div class="flex-grow text-center">
			<p class="border-b-2 text-center font-bold">Tax Region</p>
			<p>{{ $invoice->customer->taxRegion->name }}</p>
		</div>
	</div>
	<div class="mb-3 flex flex-col justify-evenly md:my-3 md:flex-row">
		<div class="flex-grow text-center">
		<p class="border-b-2 text-center font-bold">Address</p>
		<p><a href='https://maps.google.com/?q={{ $invoice->customer->address }}'>{{ $invoice->customer->address ?? '-' }}</a></p>
		</div>
		<div class="flex-grow text-center">
		<p class="border-b-2 text-center font-bold">Province/State</p>
		<p>{{ $invoice->customer->province ?? '-' }}</p>
		</div>
		<div class="flex-grow text-center">
		<p class="border-b-2 text-center font-bold">Country</p>
		<p>{{ $invoice->customer->country ?? '-' }}</p>
		</div>
	</div>

	@php
		$headers = [
			(object) array('name' => 'Description', 'style' => 'text-left w-4/12'),
			(object) array('name' => 'Price', 'style' => 'text-right'),
			(object) array('name' => 'Discount', 'style' => 'text-right'),
			(object) array('name' => 'Quantity', 'style' => 'text-center'),
			(object) array('name' => 'Refunded', 'style' => 'text-center'),
			(object) array('name' => 'Total', 'style' => 'text-right')
		];
	@endphp

	<div class="md:mt-3">
		<h2 class="md:mb-4 text-xl font-medium">Items</h2>
		<x-table.table-view :headers="$headers">
			@foreach ($invoice->invoice_row as $invoice_row)
				@unless ($invoice_row->deleted)
					<x-table.table-row>
						<x-table.table-data class="font-base col-start-1 col-end-3 row-start-1 row-end-2 py-0 text-xl md:text-gray-900 md:py-4">
							<span class="border-b-2 rounded-t-lg pb-1 px-2 text-sm text-gray-900 md:hidden bg-gray-100 pt-2">Description</span>
							<span class="px-2 md:inline-block">{{ $invoice_row->description }}</span>
						</x-table.table-data>
						<x-table.table-data class="md:text-right col-start-1 col-end-2 row-start-2 row-end-3 px-2 py-4 text-xl">
							<span class="border-b-2 pb-1 text-sm text-gray-900 md:hidden">Price</span>
							<span class="px-2 pt-2 md:before:content-[''] before:content-['$']">{{ FormatOutput::moneyFormat($invoice_row->price) }}</span>
						</x-table.table-data>
						<x-table.table-data class="md:text-right col-start-2 col-end-3 row-start-2 row-end-3 px-2 py-4 text-xl">
							<span class="border-b-2 pb-1 text-sm text-gray-900 md:hidden">Discount</span>
							<span class="px-2 pt-2">{{ $invoice_row->discount_string }}</span>
						</x-table.table-data>
						<x-table.table-data class="md:text-center col-start-1 col-end-2 row-start-3 row-end-4 px-2 py-4 text-xl">
							<span class="border-b-2 pb-1 text-sm text-gray-900 md:hidden">Quantity</span>
							<span class="px-2 pt-2">{{ $invoice_row->quantity }}</span>
						</x-table.table-data>
						<x-table.table-data class="md:text-center col-start-2 col-end-3 row-start-3 row-end-4 px-2 py-4 text-xl">
							<span class="border-b-2 pb-1 text-sm text-gray-900 md:hidden">Refunded</span>
							<span class="px-2 pt-2">{{ $invoice_row->refund_string }}</span>
						</x-table.table-data>
						<x-table.table-data class="col-start-1 col-end-3 row-start-4 row-end-5 px-2 py-0 pb-4 text-center text-xl md:py-4 md:text-right">
							<span class="border-b-2 pb-1 text-sm text-gray-900 md:hidden">Total</span>
							<span class="px-2 pt-2 md:before:content-[''] before:content-['$']">{{ FormatOutput::moneyFormat($invoice_row->total) }}</span>
						</x-table.table-data>
					</x-table.table-row>
				@endunless
			@endforeach
		</x-table-view>
	</div>
	<div class="flex flex-row justify-end">
		<table class="grid grid-cols-2 text-left text-sm md:table md:table-fixed md:w-1/2 xl:w-5/12">
			<tbody class="contents md:table-row-group">
				<tr class="contents border-b-2 md:table-row hover:bg-gray-200 ">
					<th scope="row" class="col-start-1 col-end-2 col-span-2 mx-5 text-lg font-medium text-right md:w-1/2">Gross Total</th>
					<td class="col-start-2 col-end-3 col-span-2 text-right text-xl md:table-cell px-4"><span class="float-left mr-5 px-5 text-right">$</span>{{ FormatOutput::moneyFormat($invoice->gross_total) }}</td>
				</tr>
				<tr class="contents md:table-row hover:bg-gray-200 ">
					<th scope="row" class="col-start-1 col-end-2 col-span-2 mx-5 text-lg font-medium text-right md:w-1/2">Shipping &amp; Handling</th>
					<td class="col-start-2 col-end-3 col-span-2 text-right text-xl md:table-cell px-4">{{ FormatOutput::moneyFormat($invoice->shipping_handling) ?? 0.00 }}</td>
				</tr>
				<tr class="contents md:table-row hover:bg-gray-200 ">
					<th scope="row" class="col-start-1 col-end-2 col-span-2 mx-5 text-lg font-medium text-right md:w-1/2">Invoice Discount</th>
					<td class="col-start-2 col-end-3 col-span-2 text-right text-xl md:table-cell px-4">{{ $invoice->discount_string }}</td>
				</tr>
				<tr class="contents border-b-2 md:table-row hover:bg-gray-200 ">
					<th scope="row" class="col-start-1 col-end-2 col-span-2 mx-5 text-lg font-medium text-right md:w-1/2">Before Tax</th>
					<td class="col-start-2 col-end-3 col-span-2 text-right text-xl md:table-cell px-4"><span class="float-left mr-5 px-5 text-right">$</span>{{ FormatOutput::moneyFormat($invoice->before_tax) }}</td>
				</tr>
				@foreach ($invoice->customer->tax as $tax)
				<tr class="contents md:table-row hover:bg-gray-200 ">
					<th scope="row" class="col-start-1 col-end-2 col-span-2 mx-5 text-lg font-medium text-right md:w-1/2">{{ $tax->name }} ({{ $tax->value * 100 }}%)</th>
					<td class="col-start-2 col-end-3 col-span-2 text-right text-xl md:table-cell px-4">{{ FormatOutput::moneyFormat($invoice->before_tax * $tax->value) }}</td>
				</tr>
				@endforeach
				<tr class="contents border-double border-y-2 border-gray-700 md:table-row hover:bg-gray-200  text-2xl font-semibold">
					<th scope="row" class="col-start-1 col-end-2 col-span-2 mx-5 text-right md:w-1/2 uppercase ">Net Total</th>
					<td class="col-start-2 col-end-3 col-span-2 text-right md:table-cell px-4"><span class="float-left mr-5 px-5 text-right">$</span>{{ FormatOutput::moneyFormat($invoice->net_total) }}</td>
				</tr>
			<tbody>
		</table>
	</div>

	<div class="my-3 mt-5">
		<h2 class="mb-4 text-xl font-medium">Notes</h2>
		<p class="mx-5 rounded bg-gray-100 p-3">{{ $invoice->notes }}</p>
	</div>


	<div class="grid grid-cols-4 gap-y-2 md:grid-cols-8">
		<div class="col-span-3 hidden bg-gray-100 p-1 px-2 text-left text-base md:block">Description</div>
		<div class="col-span-1 hidden bg-gray-100 p-1 px-2 text-center text-base md:block">Price</div>
		<div class="col-span-1 hidden bg-gray-100 p-1 px-2 text-center text-base md:block">Discount</div>
		<div class="col-span-1 hidden bg-gray-100 p-1 px-2 text-center text-base md:block">Quantity</div>
		<div class="col-span-1 hidden bg-gray-100 p-1 px-2 text-center text-base md:block">Refunded</div>
		<div class="col-span-1 hidden bg-gray-100 p-1 px-2 text-right text-base md:block">Total</div>

		@foreach ($invoice->invoice_row as $invoice_row)
			@unless ($invoice_row->deleted)
				<p class="col-span-4 flex flex-col text-lg md:col-span-3"><span class="block bg-gray-100 p-1 text-sm md:hidden">Description</span><span class="p-2">{{ $invoice_row->description }}</span></p>
				<p class="col-span-2 flex flex-col text-center text-lg md:col-span-1"><span class="block bg-gray-100 p-1 text-sm md:hidden">Price</span><span class="p-2 md:before:content-[''] before:content-['$']"> {{ FormatOutput::moneyFormat($invoice_row->price) }}</span></p>
				<p class="col-span-2 flex flex-col text-center text-lg md:col-span-1"><span class="block bg-gray-100 p-1 text-sm md:hidden">Discount</span><span class="p-2">{{ $invoice_row->discount ?? '-' }}</span></p>
				<p class="col-span-2 flex flex-col text-center text-lg md:col-span-1"><span class="block bg-gray-100 p-1 text-sm md:hidden">Quantity</span><span class="p-2">{{ $invoice_row->quantity }}</span></p>
				<p class="col-span-2 flex flex-col text-center text-lg md:col-span-1"><span class="block bg-gray-100 p-1 text-sm md:hidden">Refunded</span><span class="p-2">{{ $invoice_row->refund ?? '-' }}</span></p>
				<p class="col-span-4 flex flex-col text-center text-lg md:col-span-1 md:text-right"><span class="block bg-gray-100 p-1 text-sm md:hidden">Total</span><span class="p-2 md:before:content-[''] before:content-['$']"> {{ FormatOutput::moneyFormat($invoice_row->total) }}</span></p>
			@endunless
		@endforeach
		<div class="col-span-2 mx-5 text-center text-lg font-medium md:col-span-7 md:text-right">Gross Total</div>
		<div class="col-span-2 text-right text-xl md:col-span-1"><span class="float-left mr-5">$</span>{{ FormatOutput::moneyFormat($invoice->gross_total) }}</div>
		<div class="col-span-2 mx-5 text-center text-lg font-medium md:col-span-7 md:text-right">Shipping &amp; Handling</div>
		<div class="col-span-2 text-right text-xl md:col-span-1">{{ FormatOutput::moneyFormat($invoice->shipping_handling) ?? 0.00 }}</div>
		<div class="col-span-2 mx-5 text-center text-lg font-medium md:col-span-7 md:text-right">Invoice Discount</div>
		<div class="col-span-2 text-right text-xl md:col-span-1">{{ $invoice->discount_string }}</div>
		<div class="col-span-2 mx-5 text-center text-lg font-medium md:col-span-7 md:text-right">Before Tax</div>
		<div class="col-span-2 text-right text-xl md:col-span-1"><span class="float-left mr-5">$</span>{{ FormatOutput::moneyFormat($invoice->before_tax) }}</div>
		@foreach ($invoice->customer->tax as $tax)
			<div class="col-span-2 mx-5 text-center text-lg font-medium md:col-span-7 md:text-right">{{ $tax->name }} ({{ $tax->value * 100 }}%)</div>
			<div class="col-span-2 text-right text-xl md:col-span-1">{{ FormatOutput::moneyFormat($invoice->before_tax * $tax->value) }}</div>
		@endforeach
		<div class="col-span-2 mx-5 text-center text-lg font-medium md:col-span-7 md:text-right">Net Total</div>
		<div class="col-span-2 text-right text-xl md:col-span-1"><span class="float-left mr-5">$</span>{{ FormatOutput::moneyFormat($invoice->net_total) }}</div>
	</div>




	
</div>
@endsection

