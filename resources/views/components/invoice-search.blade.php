@props(['salespeople', 'companies', 'customers', 'statuses'])
<form action="{{ route('invoices.index') }}" method="GET">
	<label for="query">Search</label>
	<input type="text" name="query" id="query" placeholder="Invoice Number, Description, Notes" />
	<label for="salesperson">Salesperson</label>
	<select name="salesperson_id" id="salesperson">
		<option value="">-</option>
		@foreach ($salespeople as $salesperson)
			<option {{request('salesperson_id') == $salesperson->id ? 'selected' : ''}} value="{{ $salesperson->id }}">{{ $salesperson->name }}</option>
		@endforeach
	</select>
	<label for="company">Company</label>
	<select name="company_id" id="company">
		<option value="">-</option>
		@foreach ($companies as $company)
			<option {{ request('company_id') == $company->id ? 'selected' : '' }} value="{{ $company->id }}">{{ $company->name }}</option>
		@endforeach
	</select>
	<label for="customer">Customer</label>
	<select name="customer_id" id="customer">
		<option value="">-</option>
		@foreach ($customers as $customer)
			<option {{ request('customer_id') == $customer->id ? 'selected' : '' }} value="{{ $customer->id }}">{{ $customer->name }}</option>
		@endforeach	
	</select>
	<label for="status">Status</label>
	<select name="status_id" id="status">
		<option value="">-</option>
		@foreach ($statuses as $status)
			<option {{ request('status_id') == $status->id ? 'selected' : '' }} value="{{ $status->id }}">{{ $status->name }}</option>
		@endforeach	
	</select>
	<button type="submit">Search</button>
	<button>Reset</button>
</form>