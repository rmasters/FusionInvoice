<?php namespace FI\Validators;

class PaymentMethodValidator extends Validator {

	static $rules = array(
		'name'	=> 'required'
	);

}