<?php

namespace App\FormatOutput;

class FormatOutput {

	public static function moneyFormat($val) {
		return number_format(floatVal($val), 2);
	}

	public static function dollarFormat($val) {
		return '$'.number_format(floatVal($val), 2);
	}

	public static function dateFormat($date) {
		return \Carbon\Carbon::parse($date)->format('M d, Y');
	}
	
	public static function dateTimeFormat($date) {
		return \Carbon\Carbon::parse($date)->format('M d, Y h:m');
	}
}
