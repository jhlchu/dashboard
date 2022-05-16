<?php

namespace App\FormatOutput;

use Illuminate\Support\Facades\Facade;

class FormatOtuputFacade extends Facade {

	protected static function getFacadeAccessor() {
		return 'format_output';
	}

}