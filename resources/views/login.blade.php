<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<meta name="csrf-token" content="{{ csrf_token() }}">

        <title>E | Login</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
		<script src="https://cdn.tailwindcss.com"></script>
		<style type="text/tailwindcss">
			@layer utilities {
				a {
					@apply text-blue-500;
				}
			}
		</style>

    </head>
    <body>
		<main>
			<div class="flex flex-col">
				<form action="" method="POST">
					@csrf
					<label for="name">Name</label>
					<input type="text" name="name" id="name" placeholder="Name"/>
					<label for="password">Password</label>
					<input type="password" name="password" id="password">
				</form>
			</div>
		</main>

    </body>
</html>