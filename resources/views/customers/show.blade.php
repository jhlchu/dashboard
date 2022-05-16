@extends('layout')
@section('content')
<div class="flex flex-col">	
		<h3>Customer: {{ $customer->name }}</h3>
		<button>Edit</button>
		<button>Delete</button>
		<table>
			<thead>
				<td>Date Created</td>
				<td>Date Modified</td>
			</thead>
			<tr>
				<td>{{ \Carbon\Carbon::parse($customer->created_at)->format('M d, Y h:m') }}</td>
				<td>{{ \Carbon\Carbon::parse($customer->updated_at)->format('M d, Y h:m') }}</td>
			</tr>
		</table>
	
		<table>
			<thead>
				<td>Email</td>
				<td>Phone</td>
				<td>Tax Region</td>
			</thead>
			<tr>
				<td>{{ $customer->email ?? '-' }}</td>
				<td>{{ $customer->phone ?? '-' }}</td>
				<td>{{ $customer->taxRegion->name }}</td>
			</tr>
		</table>
		<table>
			<thead>
				<td>Address</td>
				<td>Province/State</td>
				<td>Country</td>
			</thead>
			<tr>
				<td>{{ $customer->address ?? '-' }}</td>
				<td>{{ $customer->province ?? '-' }}</td>
				<td>{{ $customer->country ?? '-' }}</td>
			</tr>
		</table>
		<h3>Invoice History</h3>
		@unless ($customer->invoice->isEmpty())
			<div class="md:m-3">
				<table class="grid w-full grid-cols-1 text-center md:table md:table-fixed md:text-left">
					<thead class="hidden md:table-header-group">
					<tr class="md:table-row">
						<td class="table-cell md:w-1/4 border-b-2 border-gray-200 px-2 pb-2 text-sm font-medium text-gray-900">Invoice Number</td>
						<td class="table-cell md:w-1/4 border-b-2 border-gray-200 px-2 pb-2 text-sm font-medium text-gray-900">Status</td>
						<td class="table-cell md:w-1/4 border-b-2 border-gray-200 px-2 pb-2 text-sm font-medium text-gray-900">Created</td>
						<td class="table-cell border-b-2 border-gray-200 px-2 pb-2 text-sm font-medium text-gray-900">Sales</td>
					</tr>
					</thead>
					<tbody class="contents md:table-row-group">
					@foreach ($customer->invoice as $invoice)
					<tr class="table-stripes contents md:table-row">
						<td class="py-1 pt-2 text-lg font-bold md:my-3 md:table-cell md:px-2 md:py-3 md:text-base md:font-normal"><a href="{{ route('invoices.show', ['invoice' => $invoice->invoice_number]) }}">{{ $invoice->invoice_number }}</a></td>
						<td class="pb-1 md:table-cell md:py-3 md:px-2"><x-status-badge :status="$invoice->status"/></td>
						<td class="text-sm md:table-cell md:py-3 md:text-base md:px-2">{{ \Carbon\Carbon::parse($invoice->created_at)->format('M d, Y') }}</td>
						<td class="pb-2 font-medium md:table-cell md:py-3 md:font-normal md:px-2">{{ $invoice->user->name }}</td>
						{{-- <td>${{ $invoice->net_total }}</td> --}}
					</tr>
					@endforeach
					</tbody>
				</table>
			</div>
		
		@else
			<p>No Order History</p>
		@endunless
</div>
@endsection


