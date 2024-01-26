<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Imports\FirstSheetImport;
use App\Imports\SecondSheetImport;
use App\Imports\ThirdSheetImport;
use App\Imports\ForthSheetImport;
use App\Imports\FifthSheetImport;
use App\Imports\SixthSheetImport;
use App\Imports\SeventhSheetImport;
use App\Imports\EigthSheetImport;
use App\Imports\NinthSheetImport;
use App\Imports\TenthSheetImport;
class CourseImport implements WithMultipleSheets

{

      public function sheets(): array
      {

        return [
           0 => new FirstSheetImport,
           1 => new SecondSheetImport,
           2 => new ThirdSheetImport,
           3 => new ForthSheetImport,
           4 => new FifthSheetImport,
           5 => new SixthSheetImport,
           6 => new SeventhSheetImport,
           7 => new EigthSheetImport,
           8 => new NinthSheetImport,
           8 => new TenthSheetImport,
        ];
      }

}
