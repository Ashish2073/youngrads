<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
class SecondSheetImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach($collection as $studyArea){
            if($studyArea[0]!='' && $studyArea[1]!='') $insertion[] =['name' => $studyArea[0], 'slug'=>$studyArea[1]];
        }

        $studyAreas = DB::table('study_areas')->insert($insertion);

        if($studyAreas) echo "data has been inserted";
    }
}
