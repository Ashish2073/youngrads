<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CampusProgramIntake extends Model
{
	use SoftDeletes;
	use LogsActivity;
	protected static $logOnlyDirty = true;
	protected static $logFillable = true;
	protected $guarded = [];

	protected $table = "campus_program_intakes";
	protected $softDelete = true;

	public function getIntake()
	{
		return $this->hasMany('App\Models\Intake', 'id', 'intake_id');
	}

	public function intake()
	{
		return $this->belongsTo('App\Models\Intake', 'intake_id');
	}

	public function getActivitylogOptions(): LogOptions
	{
		//use Spatie\Activitylog\LogOptions
		return LogOptions::defaults();
	}

}
