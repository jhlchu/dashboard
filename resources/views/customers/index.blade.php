@extends('layout')
@section('content')
	@unless ($customers->count() === 0)
		<table>
			<thead>
				<td>Name</td>
				<td>Email</td>
				<td>Phone</td>
				<td>Tax Region</td>
				<td>Address</td>
				<td>Province/State</td>
				<td>Country</td>
			</thead>
				@foreach ($customers as $customer)
					<tr>
						<td><a href="{{ route('customers.show', ['customer' => $customer->id]) }}">{{ $customer->name ?? '-' }}</a></td>
						<td>{{ $customer->email ?? '-' }}</td>
						<td>{{ $customer->phone ?? '-' }}</td>
						<td>{{ $customer->taxRegion->name }}</td>
						<td>{{ $customer->address ?? '-' }}</td>
						<td>{{ $customer->province ?? '-' }}</td>
						<td>{{ $customer->country ?? '-' }}</td>
					</tr>
				@endforeach
		</table>
	@else
		<h1>No Customers found</h1>
	@endunless
@endsection