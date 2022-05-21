
const colors = require('tailwindcss/colors');
//const { red, green, blue, gray, purple, } = require('tailwindcss/colors')

module.exports = {
	safelist: [
		{ pattern: /bg-red-.+/ },
		{ pattern: /bg-yellow-.+/ },
		{ pattern: /bg-green-.+/ },
		{ pattern: /bg-blue-.+/ },
		{ pattern: /bg-purple-.+/ },
		{ pattern: /bg-gray-.+/ },
	],

	content: [
		'./storage/framework/views/*.php',
		'./resources/**/*.blade.php',
		'./resources/**/*.js',
		'./resources/**/*.vue',
		"./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
		"./node_modules/flowbite/**/*.js",
	],
	theme: {
		colors: {
		},
		extend: {},
	},
	plugins: [
		require('flowbite/plugin')
	],
}