<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;

class CampusProgram extends Model
{
	protected $table = "campus_programs";
	use LogsActivity;
	use Notifiable;
	use SoftDeletes;
	protected static $logOnlyDirty = true;
	protected static $logFillable = true;
	protected $softDelete = true;
	protected $guarded = [];

	function getId()
	{
		return $this->id;
	}

	function getProgram()
	{
		return $this->hasOne('App\Models\Program', 'id', 'program_id');
	}

	function getIntakeId()
	{
		return $this->hasMany('App\Models\CampusProgramIntake', 'campus_program_id', 'id');
	}

	public function campus()
	{
		return $this->belongsTo('App\Models\Campus');
	}

	public function program()
	{
		return $this->belongsTo('App\Models\Program');
	}

	public function intakes()
	{
		return $this->hasMany('App\Models\CampusProgramIntake', 'campus_program_id');
	}

	public function fees()
	{
		return $this->hasMany('App\Models\CampusProgramFee', 'campus_program_id');
	}

	public function tests()
	{
		return $this->hasMany('App\Models\CampusProgramTest', 'campus_program_id');
	}

	public function userApplication()
	{
		return $this->hasMany('App\Models\UserApplication', 'campus_program_id', 'id');
	}

	public static function getCampusProgramNameIdArr()
	{
		$records = CampusProgram::all();
		$nameIdArr = [];
		foreach ($records as $record) {
			$nameIdArr[$record->campus_id . "__" . $record->program_id] = $record->id;
		}
		return $nameIdArr;
	}

	public function getActivitylogOptions(): LogOptions
	{
		//use Spatie\Activitylog\LogOptions
		return LogOptions::defaults();
	}


}
