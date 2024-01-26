<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
class ForthSheetImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach($collection as $program){
            $insertion[] = ['name' => $program[0],'program_level_id'=> $program[1] ,'study_area_id' => $program[2],'duration'=>$program[3],'course_link'=>$program[4]];
        }

        $data =  DB::table('programs')->insert($insertion);
        if($data){
            echo "Course  saved successfully";
        }

    }
}
