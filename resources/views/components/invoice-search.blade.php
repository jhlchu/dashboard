@props(['salespeople', 'companies', 'customers', 'statuses'])
<form action="{{ route('invoices.index') }}" method="GET" class='flex flex-col flex-grow'>
	<div class="p-4">
		<label for="table-search" class="sr-only">Search</label>
		<div class="relative mt-1">
			<div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
				<svg class="h-5 w-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd"
						d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
						clip-rule="evenodd"></path>
				</svg>
			</div>
			<input type="text" id="table-search"
				class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500"
				placeholder="Invoice Number, Description, Notes" name='query' id='query'>
		</div>
	</div>

	<div class="flex flex-row justify-between">
		<div class="flex flex-col mx-2 flex-grow">
			<label for="salesperson" class="mb-2 block text-sm font-medium text-gray-900">Salesperson</label>
			<select id="salesperson" name="salesperson"
				class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
				{{-- <option selected>Choose a country</option> --}}
				<option value="">-</option>
				@foreach ($salespeople as $salesperson)
					<option {{ request('salesperson_id') == $salesperson->id ? 'selected' : '' }} value="{{ $salesperson->id }}">
						{{ $salesperson->name }}</option>
				@endforeach
			</select>
		</div>
		<div class="flex flex-col mx-2 flex-grow">
			<label for="company" class="mb-2 block text-sm font-medium text-gray-900">Company</label>
			<select id="company" name="company"
				class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
				<option value="">-</option>
				@foreach ($companies as $company)
					<option {{ request('company_id') == $company->id ? 'selected' : '' }} value="{{ $company->id }}">
						{{ $company->name }}</option>
				@endforeach
			</select>
		</div>
		<div class="flex flex-col mx-2 flex-grow">
			<label for="customer" class="mb-2 block text-sm font-medium text-gray-900">Customer</label>
			<select id="customer" name="customer"
				class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
				<option value="">-</option>
				@foreach ($customers as $customer)
					<option {{ request('customer_id') == $customer->id ? 'selected' : '' }} value="{{ $customer->id }}">
						{{ $customer->name }}</option>
				@endforeach
			</select>
		</div>
		<div class="flex flex-col mx-2 flex-grow">
			<label for="status" class="mb-2 block text-sm font-medium text-gray-900">Status</label>
			<select id="status" name="status"
				class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
				<option value="">-</option>
				@foreach ($statuses as $status)
					<option {{ request('status_id') == $status->id ? 'selected' : '' }} value="{{ $status->id }}">
						{{ $status->name }}</option>
				@endforeach
			</select>
		</div>
		<button type="submit"
			class="mr-2 mb-2 rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300">Search</button>
		<button type="button"
			class="mr-2 mb-2 rounded-lg border border-gray-200 bg-white py-2.5 px-5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-200">Reset</button>
	</div>
</form>
