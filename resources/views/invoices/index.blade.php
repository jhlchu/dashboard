@php
//$request = Request::create(route('api.v1.b.show', ['booking' => 4]), 'GET');
//$request = Request::create(route('invoice.show', ['id' => $invoice->invoice_number]), 'GET');
//$request->headers->set('X-Authorization', 'xxxxx');
//dd($request)
@endphp
@extends('layout')
@section('content')
    @push('head_scripts')
        <script>
            const hashes = {{ Js::from($hashes) }};
        </script>
    @endpush
    @push('body_scripts')
        <script src="{{ asset('js/form_modal.js') }}"></script>
    @endpush
    <x-modals.form-modal />
    <div class="flex flex-col">
        <div class="flex md:flex-row flex-col">
            <div class="p-4">
                <label for="table-search" class="sr-only">Search</label>
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <input type="text" id="table-search"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-80 pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Search for items">
                </div>
            </div>
            <x-invoice-search :salespeople="$salespeople" :companies="$companies" :customers="$customers" :statuses="$statuses" />
            <a href="invoice/create" class="border-2 border-blue-500 p-1 m-1 rounded">New</a>
        </div>
        <h1 class="text-xl text-medium">Invoices</h1>
        @php
            $headers = [(object) ['name' => '#', 'style' => 'text-left'], (object) ['name' => 'Status', 'style' => 'text-left'], (object) ['name' => 'Created', 'style' => 'text-left'], (object) ['name' => 'Sales', 'style' => 'text-left'], (object) ['name' => 'Customer', 'style' => 'text-left w-2/12'], (object) ['name' => 'Company', 'style' => 'text-left w-3/12']];
        @endphp
        @unless(count($invoices) === 0)
            <div class="m-3">
                <x-table.table-view :headers="$headers">
                    @foreach ($invoices as $invoice)
                        <x-table.table-row deleted="{{ $invoice->status->name === 'Deleted' }}">
                            <x-table.table-data
                                class="font-base col-start-1 col-end-3 row-start-1 row-end-2 py-0 md:text-gray-900 md:py-4 md:px-4">
                                <span class="border-b-2 pb-1 px-2 text-sm text-gray-900 md:hidden bg-gray-100 pt-2"><a
                                        class="cursor-pointer hover:underline" data-modal-toggle="authentication-modal"
                                        onclick="open_form_modal('{{ $invoice->user->id }}','{{ $invoice->user->name }}', '{{ route('invoices.show', ['invoice' => $invoice->invoice_number]) }}', '{{ $invoice->invoice_number }}')">
                                        {{ $invoice->invoice_number }}
                                    </a></span>
                                <span class="px-2 pt-2 font-medium">
                                    <a class="cursor-pointer hover:underline" data-modal-toggle="authentication-modal"
                                        onclick="open_form_modal('{{ $invoice->user->id }}','{{ $invoice->user->name }}', '{{ route('invoices.show', ['invoice' => $invoice->invoice_number]) }}', '{{ $invoice->invoice_number }}')">
                                        {{ $invoice->invoice_number }}
                                    </a>
                                </span>
                                {{-- <span class="px-2 pt-2 font-medium"><a href="{{route('invoices.show', ['invoice' => $invoice->invoice_number])}}">{{ $invoice->invoice_number }}</a></span> --}}
                            </x-table.table-data>
                            <x-table.table-data class="col-start-1 col-end-2 row-start-2 row-end-3 px-2 py-4">
                                <span class="border-b-2 pb-1 text-sm text-gray-900 md:hidden">Status</span>
                                <span class="px-2 pt-2">
                                    <x-status-badge :status="$invoice->status" />
                                </span>
                            </x-table.table-data>
                            <x-table.table-data class="col-start-2 col-end-3 row-start-2 row-end-3 px-2 py-4 md:text-sm">
                                <span class="border-b-2 pb-1 text-sm text-gray-900 md:hidden">Created</span>
                                <span class="px-2 pt-2">{{ FormatOutput::dateFormat($invoice->created_at) }}</span>
                            </x-table.table-data>
                            <x-table.table-data class="col-start-1 col-end-2 row-start-3 row-end-4 px-2 py-4">
                                <span class="border-b-2 pb-1 text-sm text-gray-900 md:hidden">Sales</span>
                                <span
                                    class="px-2 pt-2">{{ $invoice->user->name }}{{ /* ' Auth:'.$invoice->user->getAuthPassword() */ '' }}</span>
                            </x-table.table-data>
                            <x-table.table-data class="col-start-2 col-end-3 row-start-3 row-end-4 px-2 py-4">
                                <span class="border-b-2 pb-1 text-sm text-gray-900 md:hidden">Customer</span>
                                <span class="px-2 pt-2 md:inline-block"><a
                                        href="{{ route('customers.show', ['customer' => $invoice->customer->id]) }}">{{ $invoice->customer->name }}</a></span>
                            </x-table.table-data>
                            <x-table.table-data class="col-start-1 col-end-3 row-start-4 row-end-5 px-2 py-0 pb-4 md:py-4">
                                <span class="border-b-2 pb-1 text-sm text-gray-900 md:hidden">Company</span>
                                <span class="px-2 pt-2 md:inline-block">{{ $invoice->company->name }}</span>
                            </x-table.table-data>
                        </x-table.table-row>
                    @endforeach
                    </x-table-view>


                    <table class="flex w-full flex-col md:table md:table-fixed">
                        <thead class="hidden border-b bg-white md:table-header-group">
                            <tr class="md:table-row">
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900 md:table-cell">#
                                </th>
                                {{-- <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">Auth</th> --}}
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900 md:table-cell">
                                    Status</th>
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900 md:table-cell">
                                    Created</th>
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900 md:table-cell">
                                    Sales</th>
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900 md:table-cell">
                                    Customer</th>
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900 md:table-cell">
                                    Company</th>
                                {{-- <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">Total</th> --}}
                            </tr>
                        </thead>
                        <tbody class="contents md:table-row-group">
                            @foreach ($invoices as $invoice)
                                @unless($invoice->status->name === 'Deleted')
                                    <tr
                                        class="mb-3 grid grid-cols-2 rounded border border-gray-200 md:border-b p-2 shadow-md md:mb-0 md:table-row md:rounded-none md:p-0 md:shadow-none md:even:bg-gray-100 md:hover:bg-gray-200">
                                    @else
                                    <tr
                                        class="mb-3 grid grid-cols-2 rounded border border-gray-200 md:border-b p-2 shadow-md md:mb-0 md:table-row md:rounded-none md:p-0 md:shadow-none bg-red-100 md:hover:bg-gray-200">
                                    @endunless
                                    <td
                                        class="mb- col-start-1 col-end-2 row-start-1 row-end-2 m-2 mr-0 block border-b-2 pb-3 text-2xl font-medium text-gray-900 md:table-cell md:border-0 md:px-6 md:py-4 md:text-sm">
                                        <a
                                            href="{{ route('invoices.show', ['invoice' => $invoice->invoice_number]) }}">{{ $invoice->invoice_number }}</a>
                                    </td>
                                    {{-- <td><form action="invoice/{{ $invoice->invoice_number}}" method="post">@csrf<input type="text" name="pass"/><button type="submit">Submit</button></form></td> --}}
                                    <td
                                        class="col-start-2 col-end-3 row-start-1 row-end-2 m-2 ml-0 block border-b-2 pb-3 pt-2 text-right text-gray-900 md:table-cell md:border-0 md:px-6 md:py-4 md:text-left md:text-sm md:font-light">
                                        <x-status-badge :status="$invoice->status" />
                                    </td>
                                    {{-- <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
							@if ($invoice->invoice_row->each->refund_quantity->pluck('refund_quantity')->sum() > 0)
								Yes
							@else
								-
							@endif
						</td> --}}
                                    <td
                                        class="col-start-2 col-end-3 row-start-2 row-end-3 mx-2 mb-2 block font-light text-gray-900 md:table-cell md:px-6 md:py-4 md:text-sm md:font-light">
                                        <div class="flex flex-col md:block">
                                            <span class="text-sm font-semibold md:hidden">Created</span>
                                            {{ FormatOutput::dateFormat($invoice->created_at) }}
                                        </div>
                                    </td>
                                    <td
                                        class="col-start-1 col-end-2 row-start-2 row-end-3 mx-2 block font-light text-gray-900 md:table-cell md:px-6 md:py-4 md:text-sm md:font-light">
                                        <div class="flex flex-col md:block">
                                            <span class="text-sm font-semibold md:hidden">Sales</span>
                                            {{ $invoice->user->name }}{{ /* ' Auth:'.$invoice->user->getAuthPassword() */ '' }}
                                        </div>
                                    </td>
                                    {{-- User::find($invoice->user->id)->getAuthPasword() --}}
                                    {{-- <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap"><a href="/customer/{{ $invoice->customer->id }}">{{ $invoice->customer->name }}</a></td> --}}
                                    <td
                                        class="col-start-2 col-end-3 row-start-3 row-end-4 mx-2 mb-2 block font-light text-gray-900 md:table-cell md:px-6 md:py-4 md:text-sm md:font-light">
                                        <div class="flex flex-col md:block">
                                            <span class="text-sm font-semibold md:hidden">Customer</span>
                                            <a
                                                href="{{ route('customers.show', ['customer' => $invoice->customer->id]) }}">{{ $invoice->customer->name }}</a>
                                        </div>
                                    </td>
                                    <td
                                        class="col-start-1 col-end-2 row-start-3 row-end-4 mx-2 block font-light text-gray-900 md:table-cell md:px-6 md:py-4 md:text-sm md:font-light">
                                        <div class="flex flex-col md:block">
                                            <span class="text-sm font-semibold md:hidden">Company</span>
                                            {{ $invoice->company->name }}
                                        </div>
                                    </td>
                                    {{-- <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">${{ $invoice->net_total?? 0.00 }}</td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
        @else
            <p>No Invoices Found</p>
        @endunless
    </div>
    </div>
@endsection
