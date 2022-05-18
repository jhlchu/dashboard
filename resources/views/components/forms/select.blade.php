@props(['name', 'label', 'icon', 'data', 'oldIndex'])

<div {{ $attributes->merge(['class' => 'mx-3']) }}>
	<label for="{{ $name }}" class="block mb-2 text-sm font-medium text-gray-900">{{ $label }}</label>
	<div class="flex">
		<span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md material-symbols-outlined">
			{{ $icon }}
		</span>
		<select name="{{ $name }}" id="{{ $name }}"  class="rounded-none rounded-r-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5">
			@foreach ($data as $row)
				<option {{ old($name) == $row->id ? 'selected' : '' }} value="{{ $row->id }}">{{ $row->name }}</option>
			@endforeach
		</select>
	</div>
	@error($name)
		{{ $message }}
	@enderror
</div>
