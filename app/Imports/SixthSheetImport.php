<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SixthSheetImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        echo "<br>";
        echo "</hr>Architecture and Construction <hr>";
        echo "<table>";
        foreach($collection as $data){
            if($data[1] !='' || $data[2] !='' || $data[3]!='' || $data[4]!=''|| $data[5] !='' || $data[6] !='' || $data[7]!=''|| $data[8]!=''){
                echo "<tr>";
                echo "<td>".$data[0]."</td>";
                echo "<td>".$data[1]."</td>";
                echo "<td>".$data[3]."</td>";
                echo "<td>".$data[4]."</td>";
                echo "<td>".$data[5]."</td>";
                echo "<td>".$data[6]."</td>";
                echo "<td>".$data[7]."</td>";
                echo "<td>".$data[8]."</td>";
                echo "</tr>";
            }

        }
        echo "</table>";
    }
}
