@extends('layout')

@push('head_scripts')
	<script>
		const tax_regions = {{ Js::from($tax_regions) }};
		const tax_rates = {{ Js::from($taxes) }};
		const statuses = {{ Js::from($statuses) }};
		const companies = {{ Js::from($companies) }};
	</script>
@endpush

@push('body_scripts')
	<script data-cfasync="false" src="{{ asset('js/settings.js') }}"></script>
@endpush
@section('content')
<div class="border-b border-gray-200 ">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 ">
        <li class="mr-2">
            <a href="#" id="profile-tab-example" class="inline-flex p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 group">
                <svg class="mr-2 w-5 h-5 text-gray-400 group-hover:text-gray-500 " fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path></svg>Profile
            </a>
        </li>
        <li class="mr-2">
            <a href="#" id="dashboard-tab-example" class="inline-flex p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 group">
                <svg class="mr-2 w-5 h-5 text-blue-600 " fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>Dashboard
            </a>
        </li>
        <li class="mr-2">
            <a href="#" id="settings-tab-example" class="inline-flex p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 group">
                <svg class="mr-2 w-5 h-5 text-gray-400 group-hover:text-gray-500 " fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z"></path></svg>Settings
            </a>
        </li>
        <li class="mr-2">
            <a href="#" id="contacts-tab-example" class="inline-flex p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 group">
                <svg class="mr-2 w-5 h-5 text-gray-400 group-hover:text-gray-500 " fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path></svg>Contacts
            </a>
        </li>
        <li>
            <a class="inline-block p-4 text-gray-400 rounded-t-lg cursor-not-allowed dark:text-gray-500">Disabled</a>
        </li>
    </ul>
</div>
<div id="tabContentExample">
    <div class="hidden p-4 bg-gray-50 rounded-lg" id="profile-example" role="tabpanel" aria-labelledby="profile-tab-example">
		Tax Regions
        <p class="text-sm text-gray-500">This is some placeholder content the <strong class="font-medium text-gray-800 ">Profile tab's associated content</strong>. Clicking another tab will toggle the visibility of this one for the next. The tab JavaScript swaps classes to control the content visibility and styling.</p>
    </div>
    <div class="hidden p-4 bg-gray-50 rounded-lg" id="dashboard-example" role="tabpanel" aria-labelledby="dashboard-tab-example">
		Tax Rates
        <p class="text-sm text-gray-500">This is some placeholder content the <strong class="font-medium text-gray-800 ">Dashboard tab's associated content</strong>. Clicking another tab will toggle the visibility of this one for the next. The tab JavaScript swaps classes to control the content visibility and styling.</p>
    </div>
    <div class="hidden p-4 bg-gray-50 rounded-lg" id="settings-example" role="tabpanel" aria-labelledby="settings-tab-example">
		Companies
        <p class="text-sm text-gray-500">This is some placeholder content the <strong class="font-medium text-gray-800 ">Settings tab's associated content</strong>. Clicking another tab will toggle the visibility of this one for the next. The tab JavaScript swaps classes to control the content visibility and styling.</p>
    </div>
    <div class="hidden p-4 bg-gray-50 rounded-lg" id="contacts-example" role="tabpanel" aria-labelledby="contacts-tab-example">
		Statuses
        <p class="text-sm text-gray-500">This is some placeholder content the <strong class="font-medium text-gray-800 ">Contacts tab's associated content</strong>. Clicking another tab will toggle the visibility of this one for the next. The tab JavaScript swaps classes to control the content visibility and styling.</p>
    </div>
</div>
@endsection