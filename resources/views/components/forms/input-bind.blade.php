@props(['name', 'label', 'type', 'model', 'icon'])
<div {{ $attributes->merge(['class' => 'md:mx-3']) }}>
	<label for="{{ $name }}" class="block mb-2 text-sm font-medium text-gray-900 ">{{ $label }}</label>
	<div class="flex">
		<span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md material-symbols-outlined">
			{{ $icon }}
		</span>
		<input type="search" name="{{ $name }}" id="{{ $name }}" x-bind:value="{{ $model }}.{{ $name }}" class="rounded-none rounded-r-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5"  autocomplete="nop">
	</div>
	@error($name)
		{{ $message }}
	@enderror
</div>