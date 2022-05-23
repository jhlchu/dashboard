@extends('layout')
@push('head_scripts')
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<script data-cfasync="false" type="text/javascript">
		const old_company           = "{{ old('company_id') ?? null }}";
		const old_user              = "{{ old('salesperson_id') ?? null }}";
		const old_name              = "{{ old('name') ?? null }}";
		const old_email             = "{{ old('email') ?? null }}";
		const old_phone             = "{{ old('phone') ?? null }}";
		const old_address           = "{{ old('address') ?? null }}";
		const old_province          = "{{ old('province') ?? null }}";
		const old_country           = "{{ old('country') ?? null }}";
		const old_tax_region        = "{{ old('tax_region') ?? null }}";
		const old_cart              = JSON.parse('{!! old('invoice_cart') ?? "[]" !!}');
		const old_shipping_handling = "{{ old('shipping_handling') ?? null }}";
		const old_invoice_discount  = "{{ old('discount') ?? null }}";

		const taxes = {{ Js::from($taxes) }};
		const invoice_route = '{{ route("invoices.index") }}';
	</script>
@endpush
@push('body_scripts')
	<script data-cfasync="false" src="{{ asset('js/cart.js') }}"></script>
@endpush
@section('content')
	<div class="flex flex-col max-w-[80%] m-auto">
		<form class="relative h-full" method="POST" action="{{route('invoices.store')}}" autocomplete="nop" id="invoice_form" @submit.prevent="console.log('abcd')">
			@csrf

			<h2 class="border-b-2 border-gray-700 text-lg p-3 font-medium my-3 ">Company Information</h2>
			<div class="flex md:flex-row m-3 mb-5 flex-col">
				<x-forms.select name="company_id" label="Company" :data="$companies" icon="apartment" oldIndex=old_company />
				<x-forms.select name="salesperson_id" label="Salesperson" :data="$salespeople" icon="group" oldIndex=old_user class="md:w-[200px] md:mt-0 mt-3" />
			</div>

			<h2 class="border-b-2 border-gray-700 text-lg p-3 font-medium">Customer Information</h2>
			<div class="flex flex-col mb-2" x-data="invoice">
				<div class="flex md:flex-row m-3 flex-col">
					<div class="relative md:w-fit md:mx-3 flex-grow">
						<label for="customer" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
						<div class="flex">
							<span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md material-symbols-outlined">person</span>
							<input type="search" name="name" id="customer" x-bind:value="current_customer.name" @input.debounce="searchCustomers" @blur.debounce="unselect" @focus.debounce="searchCustomers" class="rounded-none rounded-r-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5" required="required" autocomplete="nop" />
						</div>
						@error('name')
							{{ $message }}
						@enderror
						<div class="absolute top-10 left-0 right-0 mt-8" x-show="show_suggestions">
							<ul>
								<template x-for="customer in customer_list">
								<li class="cursor-pointer hover:bg-gray-200 border-2 border-gray-200 bg-gray-100 last:rounded-b-lg" @click="selectCustomer(customer.id)">
									<div class="p-1 px-2 flex flex-row justify-between">
										<div class="flex flex-col">
											<p class="font-semibold" x-text="customer.name"></p>
											<p><span class="mr-1 font-thin text-sm">TAX:</span><span x-text="getTaxName(parseInt(customer.tax_region))"></span></p>
										</div>
										<div class="flex flex-col">
											<p class="font-gray-600 font-thin text-sm" x-text="customer.address"></p>
											<p class="font-gray-600 font-thin text-sm" ><span x-text="customer.province"></span>-<span x-text="customer.country"></span></p>
											<p class="font-gray-600 font-thin text-sm"><span x-text="customer.email"></span><span x-text="customer.phone"></span></p>
										</div>
									</div>
								</li>
									{{-- <li class="cursor-pointer hover:bg-blue-700 border-b-2 last:border-b-0 border-green-700 bg-blue-300 last:rounded-b-lg" x-text="customer.name" @click="selectCustomer(customer.id)"></li> --}}
								</template>
							</ul>
						</div>
					</div>
					<x-forms.input-bind name="email" label="Email" type="search" model="current_customer" icon="alternate_email" />
					<x-forms.input-bind name="phone" label="Phone" type="search" model="current_customer" icon="android_dialer" />
				</div>
				<div class="md:m-3">
					<x-forms.input-bind name="address" label="Address" type="search" model="current_customer" icon="home" />
				</div>
				<div class="flex flex-col md:flex-row md:m-3">
					<x-forms.input-bind name="province" label="Province" type="search" model="current_customer" icon="map" class="flex-grow" />
					<x-forms.input-bind name="country" label="Country" type="search" model="current_customer" icon="public" class="flex-grow" />

					<div class='mx-3 md:w-[200px]'>
						<label for="tax_region" class="block mb-2 text-sm font-medium text-gray-900">Tax Region</label>
						<div class="flex">
							<span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md material-symbols-outlined">
								payments
							</span>
							<select name="tax_region" id="tax_region" x-model="current_customer.tax_region" class="rounded-none rounded-r-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5" @change="changeTax" >
								@foreach ($taxes as $row)
									<option {{ old('tax_region') == $row->id ? 'selected' : '' }} value="{{ $row->id }}">{{ $row->name }}</option>
								@endforeach
							</select>
						</div>
						@error('tax_region')
							{{ $message }}
						@enderror
					</div>

					{{-- <x-forms.select name="tax_region" label="Tax Region" :data="$tax_regions" icon="payments" oldIndex=0 @change="searchTax" /> --}}
				</div>

			<h2 class="border-b-2 border-gray-700 text-lg p-3 font-medium">Invoice Cart</h2>
			<div class="flex flex-col m-3">
				<input type="hidden" name="invoice_cart" x-bind:value="invoice_cart" value=""></input>
				@error('invoice_cart')
					{{ $message }}
				@enderror
				@error('invoice_cart', 'cart')
					{{ $message }}
				@enderror
				<table class="md:table-fixed w-full flex flex-col">
					<thead class="md:table-row-group md:mb-6">
						<tr x-on:keydown.prevent.enter="" class="md:table-row flex flex-col">
							<td class="hidden md:table-cell md:w-[30px]">{{-- <button @click.prevent="addCartRow" >+</button> --}}</td>
							<td class="md:my-0 my-2">
								<div class='mx-1'>
									<div class="flex">
										<input type="text" placeholder="Description" class="rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 text-left" x-model="new_cart_row.description" @keyup.enter="addCartRow" id="input_description"/>
									</div>
									@error('description', 'cart')
										{{ $message }}
									@enderror
								</div>
							</td>
							<td class="md:table-cell md:w-2/12 md:my-0 my-2">
								<div class='mx-1'>
									<div class="flex">
										<span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md material-symbols-outlined">
											attach_money
										</span>
										<input type="number" placeholder="Price" class="rounded-none rounded-r-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 text-right" x-model="new_cart_row.price" @keyup.enter="addCartRow" />
									</div>
									@error('price', 'cart')
										{{ $message }}
									@enderror
								</div>
							</td>
							<td class="md:table-cell md:w-2/12 md:my-0 my-2">
								<div class='mx-1'>
									<div class="flex">
										<span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md">
											<span class="material-symbols-outlined text-sm">attach_money</span>/<span class="material-symbols-outlined text-sm">percent</span>
										</span>
										<input type="text" placeholder="Discount" class="rounded-none rounded-r-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 text-right" x-model="new_cart_row.discount" @keyup.enter="addCartRow" />
									</div>
								</div>
							</td>
							<td class="md:table-cell md:w-[140px] md:my-0 my-2">
								<div class='mx-1'>
									<div class="flex">
										<span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md material-symbols-outlined">
											tag
										</span>
										<input type="number" placeholder="Quantity" class="rounded-none rounded-r-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 text-center" x-model="new_cart_row.quantity" @keyup.enter="addCartRow" />
									</div>
									@error('quantity', 'cart')
										{{ $message }}
									@enderror
								</div>
							</td>
							<td class="md:w-[150px] md:my-0 my-2">
								<p x-text="twoDigitString(calculateTotal(new_cart_row.price, new_cart_row.discount, new_cart_row.quantity))" class="before:content-['$'] my-2 mx-1 p-1 text-right text-lg"></p>
							</td>
							
						</tr>
					</thead>
					<tbody class="md:table-row-group">
						<tr>
							<th class="text-center font-thin text-sm pt-5"></th>
							<th class="text-left font-thin text-sm pt-5">Description</th>
							<th class="text-right font-thin text-sm pt-5">Price</th>
							<th class="text-right font-thin text-sm pt-5">Discount</th>
							<th class="text-center font-thin text-sm pt-5">Quantity</th>
							<th class="text-right font-thin text-sm pt-5">Total</th>
						</tr>
						<template x-for="cart_row in cart" x-effect="updateJson()">
							<tr x-on:keydown.prevent.enter="" :key="cart_row.id" class="hover:bg-gray-200">
								<td class="text-center"><button type="button" @click="removeCartRow(cart_row)"><span class="material-symbols-outlined text-center text-red-500 font-semibold">close</span></button></td>
								<td><input type="text" class="border-none focus:border-b-4 hover:bg-gray-100 bg-transparent border-gray-100 p-1 text-left min-w-0 w-full mx-2" x-model="cart_row.description" :value="cart_row.description" required="required" /></td>
								<td><input type="number" class="border-none focus:border-b-4 hover:bg-gray-100 bg-transparent border-gray-100 p-1 text-right min-w-0 w-full mx-2" x-model="cart_row.price" required="required" /></td>
								<td><input type="text" class="border-none focus:border-b-4 hover:bg-gray-100 bg-transparent border-gray-100 p-1 text-right min-w-0 w-full mx-2" x-model="cart_row.discount" /></td>
								<td><input type="number" class="border-none focus:border-b-4 hover:bg-gray-100 bg-transparent border-gray-100 p-1 text-center min-w-0 w-full mx-2" x-model="cart_row.quantity" required="required" /></td>
								<td><p x-text="twoDigitString(calculateTotal(cart_row.price, cart_row.discount, cart_row.quantity))" class="mx-1 p-1 text-right"></p></td>
							</tr>
						</template>
					</tbody>
				</table>
				<div class="md:flex md:flex-row md:justify-end">
					<table class="md:table md:w-1/2 mt-5">
						<tbody class="md:table-row-group">
							<tr class="border-b-2 border-gray-700">
									<td class='py-3'><p class="text-right">Gross Total</p></td>
									<td class='py-3'><p class="text-right" x-text="money(grossTotal())"></p></td>
								</tr>
								<tr x-on:keydown.prevent.enter="" class="md:table-row flex flex-col">
									<td class='py-3'><p class="text-right" ><span class="material-symbols-outlined mr-2">local_shipping</span>Shipping & Handling</p></td>
									<td class='py-3'>
										<div class='ml-3'>
											<div class="flex">
												<span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md material-symbols-outlined">
													attach_money
												</span>
												<input type="number" name="shipping_handling" id="shipping_handling" x-model="shipping_handling" class="rounded-none rounded-r-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 text-right" >
											</div>
											@error('shipping_handling')
												{{ $message }}
											@enderror
										</div>
									</td>
								</tr>
								<tr x-on:keydown.prevent.enter="">
									{{-- <td><input type="text" name="discount_string" placeholder="$ or %" x-model="invoice_discount" /></td> --}}
									<td class='py-3'><p class="text-right" ><span class="material-symbols-outlined mr-2">sell</span>Invoice Discount</p></td>
									<td class='py-3'>
										<div class='ml-3'>
											<div class="flex">
												<span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md">
													<span class="material-symbols-outlined text-sm">attach_money</span>/<span class="material-symbols-outlined text-sm">percent</span>
												</span>
												<input type="text" name="discount" id="discount" x-model="invoice_discount" class="rounded-none rounded-r-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 text-right" >
											</div>
											@error('discount')
												{{ $message }}
											@enderror
										</div>
									</td>
								</tr>
								<tr>
									<td class='py-3'><p class="text-right">Before Tax</p></td>
									<td class='py-3'><p class="text-right" x-text="money(beforeTax())"></p></td>
								</tr>
								<template x-for="tax in taxes" :key="tax.name">
									<tr>
										<td class="py-2"><p class="text-right" x-text="tax.name + ' (' + twoDigitString(tax.value * 100) + '%)'"></p></td>
										<td class="py-2"><p class="text-right" x-text="twoDigitString(grossTotal() * tax.value)"></p></td>
									</tr>
								</template>
								<tr class="border-y-4 border-black">
									<td class="py-3"><p class="md:font-bold md:text-2xl md:text-right">NET TOTAL</p></td>
									<td class="py-3"><p class="md:text-2xl md:font-bold md:text-right " x-text="money(netTotal())"></p></td>
								</tr>
						</tbody>
					</table>
				</div>
			</div>
			</div>
			<h2 class="border-b-2 border-gray-700 text-lg p-3 mb-2 font-medium">Notes</h2>
			<textarea class="border-2 border-gray-300 rounded-lg p-3 my-3 w-full mb-20" type="text" rows="10" name="notes" placeholder="Enter notes here.">{{ old('notes') }}</textarea>
			<div class="fixed bottom-0 left-0 right-0 py-3 bg-gray-100 shadow-lg shadow-black">
				<div class="flex flex-row justify-center">
					@foreach ($statuses as $status)
						<button id="button_submit" class="text-white {{ $status->color }}-700 hover:{{ $status->color }}-800 focus:ring-4 focus:outline-none focus:bg-{{ $status->color }}-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center mr-2 w-[250px]" name="status_id" value="{{ $status->id }}" @click.prevent="">
							<span class="material-symbols-outlined">{{ $status->icon }}</span>{{ $status->name }}
						</button>
						{{-- @if (preg_match("(Draft|Completed|Paid)", $status->name) === 1) --}}{{-- @endif --}}
					@endforeach
					<a id="button_cancel" class="cursor-pointer text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none w-[250px] focus:bg-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center mr-2" ><span class="material-symbols-outlined">cancel</span>Cancel</a>
					<button id="button_cancel" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none w-[250px] focus:bg-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center mr-2" name="status" value="cancel">
						<span class="material-symbols-outlined">cancel</span>Cancel
					</button>
				</div>
			</div>
		</form>
		
	</div>

{{--
	  <label for="website-admin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Username</label>
<div class="flex">
  <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
    @
  </span>
  <input type="text" id="website-admin" class="rounded-none rounded-r-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Bonnie Green">
</div>
 				<button disabled type="button" class="  cursor-not-allowed text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 inline-flex items-center">
					<svg role="status" class="inline w-4 h-4 mr-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
					<path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
					</svg>
					Loading...
				</button> --}}
{{-- {{ $customers }} --}}
@endsection

{{-- 
<table class="flex w-full flex-col text-left text-sm md:table md:table-fixed">
  <thead>
    <tr x-on:keydown.prevent.enter="">
      <td><input type="text" placeholder="Enter Description" class="my-2 mx-1 w-full border-b-2 border-gray-400 bg-gray-100 p-1" x-model="new_cart_row.description" @keyup.enter="addCartRow" id="input_description" /></td>
      <td class="w-2/12"><input type="number" placeholder="Enter Price" class="my-2 mx-1 w-full border-b-2 border-gray-400 bg-gray-100 p-1" x-model="new_cart_row.price" @keyup.enter="addCartRow" /></td>
      <td class="w-2/12"><input type="text" placeholder="Enter Discount ($ or %)" class="my-2 mx-1 w-full border-b-2 border-gray-400 bg-gray-100 p-1" x-model="new_cart_row.discount" @keyup.enter="addCartRow" /></td>
      <td class="w-[10%]"><input type="number" placeholder="Enter Quantity" class="my-2 mx-1 w-full border-b-2 border-gray-400 bg-gray-100 p-1" x-model="new_cart_row.quantity" @keyup.enter="addCartRow" /></td>
      <td class="w-[13%]" colspan=""><p x-text="twoDigitString(calculateTotal(new_cart_row.price, new_cart_row.discount, new_cart_row.quantity))" class="my-2 mx-1 p-1 text-right text-lg font-medium before:content-['$']">300000.21</p></td>
      <td class="w-[8%]"></td>
    </tr>
  </thead>
  <thead class="hidden bg-gray-50 text-xs uppercase md:table-header-group md:text-gray-700">
    <tr class="md:table-row md:border-b-2">
      <th scope="col" class="table-cell px-4 py-3">Description</th>
      <th scope="col" class="table-cell px-4 py-3">Price</th>
      <th scope="col" class="table-cell px-4 py-3">Discount</th>
      <th scope="col" class="table-cell px-4 py-3">Quantity</th>
      <th scope="col" class="table-cell px-4 py-3 text-right">Total</th>
      <th scope="col" class="table-cell px-4 py-3">Delete</th>
    </tr>
  </thead>
  <tbody class="contents md:table-row-group">
    <tr class="mb-3 grid grid-cols-2 rounded-lg border-2 border-b border-gray-100 bg-white shadow-md md:mb-0 md:table-row md:rounded-none md:border-0 md:p-0 md:px-0 md:shadow-none last:md:border-b-4 last:md:border-gray-500 md:even:bg-gray-100 md:hover:bg-gray-200">
      <td class="flex flex-col px-2 py-2 md:table-cell md:text-lg">
        <input type="text" class="w-full border-b-2 border-gray-400 bg-gray-100 p-1" x-model="cart_row.description" :value="cart_row.description" />
      </td>
      <td class="flex flex-col px-2 py-2 md:table-cell md:text-lg">
        <input type="number" class="w-full border-b-2 border-gray-400 bg-gray-100 p-1 text-right" x-model="cart_row.price" />
      </td>
      <td class="flex flex-col px-2 py-2 md:table-cell md:text-lg">
        <input type="text" class="w-full border-b-2 border-gray-400 bg-gray-100 p-1 text-right" x-model="cart_row.discount" />
      </td>
      <td class="flex flex-col px-2 py-2 md:table-cell md:text-lg">
        <input type="number" class="w-full border-b-2 border-gray-400 bg-gray-100 p-1 text-center" x-model="cart_row.quantity" />
      </td>
      <td class="flex flex-col px-2 py-2 md:table-cell md:text-lg">
        <p x-text="twoDigitString(calculateTotal(cart_row.price, cart_row.discount, cart_row.quantity))" class="p-1 text-right before:content-['$']">300000.21</p>
      </td>
      <td class="flex flex-col text-center md:table-cell md:text-lg">
        <button class="w-7 rounded-lg bg-gray-500 text-white" type="button" @click="removeCartRow(cart_row)">X</button>
      </td>
    </tr>
  </tbody>
</table>
<div class="flex flex-row justify-end">
  <table class="grid grid-cols-2 text-left text-sm md:table md:w-1/2 md:table-fixed xl:w-5/12">
    <tbody class="contents md:table-row-group">
      <tr class="contents border-b-2 hover:bg-gray-200 md:table-row">
        <th scope="row" class="col-span-2 mx-5 w-1/2 text-right text-lg font-medium">Gross Total</th>
        <td class="col-span-2 px-4 text-right text-xl md:table-cell">
          <span class="float-left mr-5 px-5 text-right">$</span>
          <p x-text="money(grossTotal())">$447.00</p>
        </td>
      </tr>
      <tr class="contents hover:bg-gray-200 md:table-row">
        <th scope="row" class="col-span-2 mx-5 w-1/2 text-right text-lg font-medium">Shipping &amp; Handling</th>
        <td class="col-span-2 px-4 text-right text-xl md:table-cell">
          <input class="border-b-2 border-gray-400 bg-gray-100 text-right" type="number" name="shipping_handling" x-model="shipping_handling" placeholder="Enter S&H" />
        </td>
      </tr>
      <tr class="contents hover:bg-gray-200 md:table-row">
        <th scope="row" class="col-span-2 mx-5 w-1/2 text-right text-lg font-medium">Invoice Discount</th>
        <td class="col-span-2 px-4 text-right text-xl md:table-cell">
          <input class="border-b-2 border-gray-400 bg-gray-100 text-right" type="text" name="invoice_discount" placeholder="$ or %" x-model="invoice_discount" />
        </td>
      </tr>
      <tr class="contents border-b-2 hover:bg-gray-200 md:table-row">
        <th scope="row" class="col-span-2 mx-5 w-1/2 text-right text-lg font-medium">Before Tax</th>
        <td class="col-span-2 px-4 text-right text-xl md:table-cell">
          <span class="float-left mr-5 px-5 text-right">$</span>
          <p x-text="money(beforeTax())">$0.00</p>
        </td>
      </tr>
      <tr class="contents hover:bg-gray-200 md:table-row">
        <th scope="row" class="col-span-2 mx-5 w-1/2 text-right text-lg font-medium"><p x-text="tax.name + ' (' + (tax.value * 100) + '%)'">GST (5.00%)</p></th>
        <td class="col-span-2 px-4 text-right text-xl md:table-cell">22.35</td>
      </tr>
      <tr class="contents hover:bg-gray-200 md:table-row">
        <th scope="row" class="col-span-2 mx-5 w-1/2 text-right text-lg font-medium"><p x-text="tax.name + ' (' + (tax.value * 100) + '%)'">PST (7.00%)</p></th>
        <td class="col-span-2 px-4 text-right text-xl md:table-cell">31.29</td>
      </tr>
      <tr class="contents border-y-2 border-double border-gray-700 text-2xl font-semibold hover:bg-gray-200 md:table-row">
        <th scope="row" class="col-span-2 mx-5 w-1/2 text-right uppercase">Net Total</th>
        <td class="col-span-2 px-4 text-right md:table-cell">
          <span class="float-left mr-5 px-5 text-right">$</span>
          <p>100000.00</p>
        </td>
      </tr>
    </tbody>
  </table>
</div>
	
	
--}}