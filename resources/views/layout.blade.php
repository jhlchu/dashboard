<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		

        <title>Element Acoustics Dashboard | @yield('title')</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="https://fonts.sandbox.google.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
		
		{{-- <script src="//unpkg.com/alpinejs" defer></script> --}}
		{{-- <script src="https://cdn.tailwindcss.com"></script> --}}

		{{-- <script src="https://unpkg.com/flowbite@1.4.5/dist/flowbite.js"></script>	 --}}
		{{-- <link rel="stylesheet" href="https://unpkg.com/flowbite@1.4.5/dist/flowbite.min.css" /> --}}

		{{-- <script data-cfasync="false" src="{{ asset('js/tailwindcss-3.0.24.min.js') }}"></script> --}}
		{{-- <script data-cfasync="false" src="{{ asset('js/flowbite-1.4.5.min.js') }}"></script> --}}
		{{-- <script data-cfasync="false" src="{{ asset('js/alpinejs-3.10.2.min.js') }}" defer></script> --}}
		{{-- <link rel="stylesheet" href="{{ asset('css/flowbite-1.4.5.min.css') }}" /> --}}

		<script data-cfasync="false" src="{{ mix('js/app.js') }}"></script>
		<link  data-cfasync="false" rel="stylesheet" href="{{ mix('css/app.css') }}" />

		@stack('head_scripts')

    </head>
	<body>
		<nav class="border-gray-200 px-2 sm:px-4 py-2.5 rounded-b bg-gray-800 w-full">
			<div class="container flex flex-wrap justify-between items-center mx-auto">
			<a href="https://dashboard.element-acoustics.com/" class="flex items-center">
				{{-- <img src="/docs/images/logo.svg" class="mr-3 h-6 sm:h-9" alt="Flowbite Logo" /> --}}
				<span class="self-center text-xl font-semibold whitespace-nowrap text-yellow-300">Element Acoustics Dashboard</span>
			</a>
			<div class="flex md:order-2">
				<button type="button" data-collapse-toggle="mobile-menu-3" aria-controls="mobile-menu-3" aria-expanded="false" class="md:hidden text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 rounded-lg text-sm p-2.5 mr-1" >
					<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
				</button>
				<div class="hidden relative md:block">
					<div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
						<svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
					</div>
					<form action="{{ route('invoices.index') }}" method="GET">
						<input type="text" name="query" id="query" class="block p-2 pl-10 w-full text-gray-900 bg-gray-50 rounded-lg border border-gray-300 sm:text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Search...">
					</form>
				</div>
				<button data-collapse-toggle="mobile-menu-3" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200" aria-controls="mobile-menu-3" aria-expanded="false">
					<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
					<svg class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
				</button>
			</div>
				<div class="hidden justify-between items-center w-full md:flex md:w-auto md:order-1" id="mobile-menu-3">
					<div class="relative mt-3 md:hidden">
						<div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
							<svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
						</div>
							<input type="text" id="search-navbar" class="block p-2 pl-10 w-full text-gray-900 bg-gray-50 rounded-lg border border-gray-300 sm:text-sm focus:ring-blue-500 focus:border-blue-500 " placeholder="Search...">
					</div>
					<ul class="flex flex-col mt-4 md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium">
						<li>
							<a href="{{ route('invoices.create') }}" class="block py-2 pr-4 pl-3 text-white border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 hover:text-yellow-200 md:p-0  " aria-current="page">New Invoice</a>
						</li>
						<li>
							<a href="{{ route('invoices.index') }}" class="block py-2 pr-4 pl-3 {{ Route::currentRouteNamed('invoices.*') ? ' text-yellow-300 md:p-0  ' : ' text-white md:p-0 hover:text-yellow-200' }}" aria-current="page">Invoices</a>
						</li>
						<li>
							<a href="{{ route('customers.index') }}" class="block py-2 pr-4 pl-3 {{ Route::currentRouteNamed('customers.*') ? ' text-yellow-300 md:p-0' : 'text-white md:p-0 hover:text-yellow-200' }}">Customers</a>
						</li>
						<li>
							<a href="{{ route('settings') }}"  class="block py-2 pr-4 pl-3 {{ Route::currentRouteNamed('settings') ? ' text-yellow-300 md:p-0' : 'text-white md:p-0 hover:text-yellow-200' }}">Settings</a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
		<main class="md:flex md:flex-col md:m-auto md:w-1/2 md:min-w-[1300px]">
			<div>@yield('content')</div>
		</main>
		@stack('body_scripts')
	</body>
</html>