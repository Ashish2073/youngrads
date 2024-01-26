<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ApplicationDocumentCountry extends Model
{
	use LogsActivity;
	protected static $logOnlyDirty = true;
	protected static $logFillable = true;
	protected $guarded = [];
	//use SoftDeletes;
	protected $table = "application_document_countries";
	//protected $softDelete = true;

	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults();
	}
	function country()
	{
		return $this->hasOne('App\Models\Country', 'id', 'country_id');
	}


}
