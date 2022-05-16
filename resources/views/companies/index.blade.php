@extends('layout')
@section('content')
<table>
	<thead>
		<tr>
			<th>Name</th>
			<th>Address 1</th>
			<th>Address 2</th>
			<th>City</th>
			<th>Phone</th>
		</tr>
		@foreach ($companies as $company)
		<tr>
			<td><a href="company/{{ $company->id }}">{{ $company->name }}</a></td>
			<td>{{ $company->address1 }}</td>
			<td>{{ $company->address2 ?? '-' }}</td>
			<td>{{ $company->city }}</td>
			<td>{{ $company->phone ?? '-' }}</td>
		</tr>
		@endforeach
	</thead>
</table>
@endsection