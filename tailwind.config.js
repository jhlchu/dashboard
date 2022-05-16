
const colors = require('tailwindcss/colors');
//const { red, green, blue, gray, purple, } = require('tailwindcss/colors')

module.exports = {
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
			'red': colors.red,

		},
		extend: {},
	},
	plugins: [
		require('flowbite/plugin')
	],
}
