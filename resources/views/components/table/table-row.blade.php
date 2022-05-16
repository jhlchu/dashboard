@props([
	'deleted' => false
])

<tr {{ $attributes->class(['bg-red-100' => $deleted, 'md:even:bg-gray-100' => !$deleted])->merge(['class' => 'last:md:border-b-4 last:md:border-gray-500 md:border-0 border-gray-100 border-2 mb-3 grid grid-cols-2 rounded-lg border-b bg-white shadow-md md:mb-0 md:table-row md:rounded-none md:shadow-none md:hover:bg-gray-200 md:p-0 md:px-0']) }} >
	{{ $slot }}
</tr>