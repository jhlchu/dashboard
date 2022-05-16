@extends('layout')
@section('content')
<table>
	<thead>
		<tr>
			<th>Name</th>
			<th>Email</th>
		</tr>
		@foreach ($users as $user)
		<tr>
			<td><a href="user/{{ $user->id }}">{{ $user->name }}</a></td>
			<td>{{ $user->email }}</td>
		</tr>
		@endforeach
	</thead>
</table>
@endsection