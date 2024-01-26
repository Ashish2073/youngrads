<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\University;
use Illuminate\Support\Facades\DB;

class FirstSheetImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $universities = new University;

        foreach ($collection as $university) {
            $insertion[] = ['name' => $university[0]];
        }

        $data = DB::table('universities')->insert($insertion);

        if ($data) {
            echo "university saved successfully";
        }

    }
}
