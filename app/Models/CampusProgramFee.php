<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CampusProgramFee extends Model
{
    use SoftDeletes;
    protected $table = 'campus_program_fees';

    use LogsActivity;
    protected static $logOnlyDirty = true;
    protected static $logFillable = true;
    protected $guarded = [];
    protected $softDelete = True;

    protected $casts = [
        // 'fee_price' => 'UNSIGNED',
    ];

    public function fee()
    {
        return $this->belongsTo('App\Models\Feetype', 'fee_type_id');
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Currency', 'fee_currency');
    }
    public function getActivitylogOptions(): LogOptions
    {
        //use Spatie\Activitylog\LogOptions
        return LogOptions::defaults();
    }

}
