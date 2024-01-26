<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
class ThirdSheetImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {

        foreach($collection as $campus){
            if($campus[0]!='' && $campus[1]!='') $insertion[] = ['name'=>$campus[0],'university_id'=>$campus[1]];
        }

        $campuses = DB::table('campus')->insert($insertion);
        if($campuses) echo "campus has been inseteded";
    }
}
