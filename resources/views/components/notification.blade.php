@if (session()->has('message'))
	<div class="mb-4 rounded-lg bg-blue-100 p-4 text-sm text-blue-700 dark:bg-blue-200 dark:text-blue-800" role="alert">
		<span class="font-medium">Info alert!</span> Change a few things up and try submitting again.
	</div>
	<div class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700 dark:bg-red-200 dark:text-red-800" role="alert">
		<span class="font-medium">Danger alert!</span> Change a few things up and try submitting again.
	</div>
	<div class="mb-4 rounded-lg bg-green-100 p-4 text-sm text-green-700 dark:bg-green-200 dark:text-green-800" role="alert">
		<span class="font-medium">Success alert!</span> Change a few things up and try submitting again.
	</div>
	<div class="mb-4 rounded-lg bg-yellow-100 p-4 text-sm text-yellow-700 dark:bg-yellow-200 dark:text-yellow-800"
		role="alert">
		<span class="font-medium">Warning alert!</span> Change a few things up and try submitting again.
	</div>
	<div class="rounded-lg bg-gray-100 p-4 text-sm text-gray-700 dark:bg-gray-700 dark:text-gray-300" role="alert">
		<span class="font-medium">Dark alert!</span> Change a few things up and try submitting again.
	</div>
@endif