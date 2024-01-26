<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;

class Country extends Model
{
	protected $table = "countries";
	public $timestamps = false;
	use LogsActivity;
	use SoftDeletes;
	public function getActivitylogOptions(): LogOptions
	{
		//use Spatie\Activitylog\LogOptions
		return LogOptions::defaults();
	}
	protected static $logOnlyDirty = true;
	protected static $logFillable = true;
	protected $softDelete = true;
	protected $guarded = [];

	public function address()
	{
		return $this->hasMany('App\Models\Address', 'country_id', 'id');
	}

	public static function getCountryIdNameArr()
	{
		$countries = Country::all();
		$countryIdNameArr = [];
		foreach ($countries as $country) {
			$countryIdNameArr[strtolower($country->name)] = $country->id;
		}
		return $countryIdNameArr;
	}



}
