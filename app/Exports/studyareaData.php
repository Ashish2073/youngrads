<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Models\Study;

class studyareaData implements FromCollection,WithHeadings,WithChunkReading
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function headings():array{
        return[
            'Study_Area',
            'Sub_Study_Area',   
        ];
    } 
 


    public function collection()
    {
        
                $data = Study::leftJoin('study_areas as sub', 'study_areas.id', '=', 'sub.parent_id')
                       ->select('study_areas.name as study_area_name','sub.name as sub_study_area_name')
                       ->where('study_areas.parent_id', 0)
                       ->get();

                       
               return $data;
  

    }

    public function chunkSize(): int
    {
        return 1000; // Adjust the chunk size based on your requirements
    }
}
