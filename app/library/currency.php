<?php

namespace App\library;

class Currency {

	public function format($number) {
		return 'THB '.number_format($number, 0, '.', ',');
	}

}
