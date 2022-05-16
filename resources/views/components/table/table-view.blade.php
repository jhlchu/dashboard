@props(['headers'])

<table {{ $attributes->merge(['class' => 'flex w-full flex-col text-left text-sm md:table md:table-fixed']) }}>
	<thead class="hidden bg-gray-50 text-xs uppercase md:text-gray-700 md:table-header-group">
		<tr class="md:table-row md:border-b-2">
			@foreach ($headers as $header)
				<th scope="col" class="px-4 py-3 table-cell {{ $header->style }}">{{ $header->name }}</th>
			@endforeach
		</tr>
	</thead>
	<tbody class="contents md:table-row-group">
		{{ $slot }}
	</tbody>
</table>