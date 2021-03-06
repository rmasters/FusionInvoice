<?php namespace FI\Storage\Eloquent\Models;

use FI\Classes\CurrencyFormatter;

class Client extends \Eloquent {
	
	protected $guarded = array('id');
	
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

	public function invoices()
	{
		return $this->hasMany('FI\Storage\Eloquent\Models\Invoice');
	}

	public function quotes()
	{
		return $this->hasMany('Fi\Storage\Eloquent\Models\Quote');
	}

	public function notes()
	{
		return $this->hasMany('FI\Storage\Eloquent\Models\ClientNote');
	}

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

	public function getFormattedBalanceAttribute()
	{
		return CurrencyFormatter::format($this->attributes['balance']);
	}

	public function getFormattedPaidAttribute()
	{
		return CurrencyFormatter::format($this->attributes['paid']);
	}

	public function getFormattedTotalAttribute()
	{
		return CurrencyFormatter::format($this->attributes['total']);
	}
	
}