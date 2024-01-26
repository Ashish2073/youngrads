<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class FifthSheetImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {

        foreach($collection as $data){
            if($data[1] !='')  $universities[] = $data[1];
            if($data[1] !='' && $data[3] !=''){
                $campuses['name'] = $data[3];
                $campuses['unversity_id'] = $data[1];
            }
        }

        //$universities = array_unique($universities,SORT_REGULAR);
        echo "<pre>";
        print_r(array_unique($universities));

    }
}
