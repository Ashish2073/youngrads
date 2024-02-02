<?php

namespace App\Imports;

use App\Models\Address;
use App\Models\University;
use App\Models\Campus;
use App\Models\CampusProgram;
use App\Models\CampusProgramFee;
use App\Models\CampusProgramIntake;
use App\Models\CampusProgramTest;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Currency;
use App\Models\Intake;
use App\Models\Program;
use App\Models\ProgramArea;
use App\Models\ProgramLevel;
use App\Models\Study;
use App\Models\Test;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

use Carbon\Carbon;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

// class CampusProgramImport implements ToCollection, WithHeadingRow, WithChunkReading, WithBatchInserts, ShouldQueue
class CampusProgramImport implements ToCollection, WithHeadingRow
{
    public $response;

    public function collection(Collection $records)
    {
        $univsNameIdArr = University::getNameIdIndexedArray();
        $univIdCampusNameArr = Campus::getUnivIdCampusNameArr();
        $programNameIdArr = Program::getNameIdArr();
        $programLevelIdArr = ProgramLevel::getNameIdArr();
        $studyAreaNameIdArr = Study::getNameIdArr();
        $subStudyNameIdArr = Study::getsubStudyNameIdArr();
        $campusProgramsArr = CampusProgram::getCampusProgramNameIdArr();
        $intakeIdNameArr = Intake::getIdNameArr();
        $feeType['admission_fee'] = 2;
        $feeType['tuition_fee'] = 3;
        $feeType['application_fee'] = 4;

        $testType['toefl'] = 1;
        $testType['ielts'] = 2;
        $testType['pte'] = 3; 
        $testType['gmat'] = 4;
        $testType['cat'] = 5;

       

        $currenciesArr = Currency::getIdNameArr();

        $sheetError = [];
        $rowNumber = 1;

       

        foreach ($records as $record) {
            $rowNumber++;
            if (empty($record['university'])) {
                continue;
            }
            // University ID
            if (!isset($univsNameIdArr[strtolower($record['university'])])) {
                Log::debug('University does not exists!' . json_encode($record));
                $sheetError[] = [
                    'message' => 'University does not exists!' . json_encode($record),
                    'record' => $record,
                    'rowNumber' => $rowNumber,
                ];
                continue;
            }
            $univId = $univsNameIdArr[strtolower($record['university'])];

            // Campus ID
            if (!isset($univIdCampusNameArr[$univId . "__" . strtolower($record['campus'])])) {
                Log::debug('Campus does not exists!' . json_encode($record));
                $sheetError[] = [
                    'message' => 'Campus does not exists!',
                    'record' => $record,
                    'rowNumber' => $rowNumber,
                ];
                continue;
            }
            $campusId = $univIdCampusNameArr[$univId . "__" . strtolower($record['campus'])];

            // Program Level ID
            if (!isset($programLevelIdArr[strtolower($record['level'])])) {
                Log::debug('Program Level does not exists!' . json_encode($record));
                $sheetError[] = [
                    'message' => 'Program Level does not exists!',
                    'record' => $record,
                    'rowNumber' => $rowNumber,
                ];
                continue;
            } else {
                $programLevelId = $programLevelIdArr[strtolower($record['level'])];
            }

            // Study Area ID
            if (!isset($studyAreaNameIdArr[strtolower($record['study_area'])])) {
                $studyArea = new Study;
                $studyArea->name = $record['study_area'];
                $studyArea->slug = Str::slug($record['study_area'], "-");
                $studyArea->save();
                $studyAreaId = $studyArea->id;
            } else {
                $studyAreaId = $studyAreaNameIdArr[strtolower($record['study_area'])];
            }

            // Program
            if (!isset($programNameIdArr[strtolower($record['program'])])) {
                $program = new Program;
            } else {
                $program = Program::find($programNameIdArr[strtolower($record['program'])]);
            }

            $program->name = $record['program'];
            $program->program_level_id = $programLevelId;
            $duration = (int) filter_var($record['duration'], FILTER_SANITIZE_NUMBER_INT);
            $program->duration = $duration;
            if (isset($studyAreaId)) {
                $program->study_area_id = $studyAreaId;
            }
            $program->save();

            $programId = $program->id;



            // Program Study Areas
            $programStudyAreaIdArr = ProgramArea::getIdNameArr($programId);

            if (!empty($record['sub_study_area'])) {
                $subStudyArea = explode(",", $record['sub_study_area']);
                foreach ($subStudyArea as $area) {
                    if (isset($subStudyNameIdArr[$studyAreaId . "__" . strtolower($area)])) {
                        $subStudyAreaId = $subStudyNameIdArr[$studyAreaId . "__" . strtolower($area)];
                    } else {
                        $subStudyAreaRecord = Study::create([
                            'name' => $area,
                            'slug' => Str::slug($area, "-"),
                            'parent_id' => $studyAreaId
                        ]);
                        $subStudyAreaId = $subStudyAreaRecord->id;
                    }

                    if (!isset($programStudyAreaIdArr["program_" . $programId . "__" . "study_area_" . $subStudyAreaId])) {
                        ProgramArea::create([
                            'program_id' => $programId,
                            'study_area_id' => $subStudyAreaId
                        ]);
                    }
                }
            }




            // Campus Program
            if (!isset($campusProgramsArr[$campusId . "__" . $programId])) {
                $campusProgram = new CampusProgram;
            } else {
                $campusProgram = CampusProgram::find($campusProgramsArr[$campusId . "__" . $programId]);
            }


            $campusProgram->campus_id = $campusId;
            $campusProgram->program_id = $programId;
            $campusProgram->entry_requirment = $record['entry_requirements'];
            $campusProgram->campus_program_duration = $duration;
            $campusProgram->website = !empty($record['website']) ? $record['website'] : null;
            $campusProgram->save();

            $campusProgramId = $campusProgram->id;

            // Campus Program Tests
            CampusProgramTest::where('campus_program_id', $campusProgramId)->delete();

        if(isset($record['entrance_exam_name']) && !empty($record['entrance_exam_name'])){

            $entrenceExamAllName=explode(',',$record['entrance_exam_name']);

           
              
            $testValues=[];
            foreach ($entrenceExamAllName as $testName) {
                $testValues[] = [
                    'test_name' => $testName,
                    'min' => (isset($record['entrance_score_min']) && !empty($record['entrance_score_min'])?$record['entrance_score_min']:null),
                    'max' =>  (isset($record['entrance_score_max']) && !empty($record['entrance_score_max'])?$record['entrance_score_max']:null),     
                ];
            }
 
       

            // if(isset($record['entrance_score_max']) && !empty($record['entrance_score_max'])){
            //     $maxScore=$record['entrance_score_max'];

            // }else{
            //     $maxScore=null; 
            // }

            // if(isset($record['entrance_score_min']) && !empty($record['entrance_score_min'])){
            //     $minScore=$record['entrance_score_min'];

            // }else{
            //     $minScore=null; 
            // }
          
            $TestInput=Test::upsert($testValues,['test_name'], ['min', 'max']);

           

            $TestAll=Test::whereIn('test_name',$entrenceExamAllName)->pluck('id');

            dd($TestInput,$TestAll);

           

            $TestIDJSON=json_decode( $TestAll,true);
           

            $TestID= $TestIDJSON[0]['id'];


            $TestSocre=(isset($record['required_score_for_enterence']) && !empty($record['required_score_for_enterence'])) ? $record['required_score_for_enterence']:null;

            $TestSocreNlt=(isset($record['required_nlt_score_for_enterence']) && !empty($record['required_nlt_score_for_enterence'])) ? $record['required_nlt_score_for_enterence']:null;

          
            CampusProgramTest::create([
                'campus_program_id' => $campusProgramId,
                'test_id' => $TestID,
                'score' =>  $TestSocre,
                'nlt_score' =>  $TestSocreNlt,
                'show_in_front' => 1,
            ]);






         }









        //  else{

        //     Log::debug('!Entrance Exam name' . json_encode($record));
        //     $sheetError[] = [
        //         'message' => 'Entraance Exam does not exists!',
        //         'record' => $record,
        //         'rowNumber' => $rowNumber,
        //     ];
        //     continue;

        // }








          



            if (isset($record['ielts_os']) && !empty($record['ielts_os'])) {
                CampusProgramTest::create([
                    'campus_program_id' => $campusProgramId,
                    'test_id' => $testType['ielts'],
                    'score' => $record['ielts_os'],
                    'nlt_score' => $record['ielts_no_bands_less_than'],
                    'show_in_front' => 1,
                ]);
            }

            if (isset($record['toefl_ibt']) && !empty($record['toefl_ibt'])) {
                CampusProgramTest::create([
                    'campus_program_id' => $campusProgramId,
                    'test_id' => $testType['toefl'],
                    'score' => $record['toefl_ibt'],
                    'nlt_score' => $record['toefl_ibtno_bands_less_than'],
                    'show_in_front' => 1,
                ]);
            }

            if (isset($record['pte_os']) && !empty($record['pte_os'])) {
                CampusProgramTest::create([
                    'campus_program_id' => $campusProgramId,
                    'test_id' => $testType['pte'],
                    'score' => $record['pte_os'],
                    'nlt_score' => $record['pte_no_bands_less_than'],
                    'show_in_front' => 1,
                ]);
            }

            if (isset($record['gmat']) && !empty($record['gmat'])) {
                CampusProgramTest::create([
                    'campus_program_id' => $campusProgramId,
                    'test_id' => $testType['gmat'],
                    'score' => $record['gmat'],
                    'show_in_front' => 1,
                ]);
            }

            if (isset($record['cat']) && !empty($record['cat'])) {
                CampusProgramTest::create([
                    'campus_program_id' => $campusProgramId,
                    'test_id' => $testType['cat'],
                    'score' => $record['cat'],
                    'show_in_front' => 1,
                ]);
            }

            // Campus Program Fees
            CampusProgramFee::where('campus_program_id', $campusProgramId)->delete();

            if (!empty(trim($record['annual_tuition_fee']))) {
                $priceArr = explode(" ", $record['annual_tuition_fee']);
                $price = $priceArr[0];
                $price = str_replace(",", "", $price);
                $price = floatval($price);

                $currency = str_replace(",", "", $priceArr[1]);
                $currency = str_replace(".", "", $currency);
                $currency = trim($currency);

                if (isset($currenciesArr[strtolower($currency)])) {
                    $currencyId = $currenciesArr[strtolower($currency)];
                    CampusProgramFee::create([
                        'campus_program_id' => $campusProgramId,
                        'fee_type_id' => $feeType['tuition_fee'],
                        'fee_price' => $price,
                        'fee_currency' => $currencyId
                    ]);
                } else {
                    Log::debug("Invalid Currency 1: " . json_encode($record));
                    $sheetError[] = [
                        'message' => "Invalid Currency 1: " . $currency . " " . $record['annual_tuition_fee'],
                        'record' => $record,
                        'rowNumber' => $rowNumber,
                    ];
                }
            }

            if (!empty(trim($record['program_fees']))) {

                $priceArr = explode(" ", $record['program_fees']);
                $price = $priceArr[0];
                $price = str_replace(",", "", $price);
                $price = floatval($price);

                $currency = str_replace(",", "", $priceArr[1]);
                $currency = str_replace(".", "", $currency);
                $currency = trim($currency);

                if (isset($currenciesArr[strtolower($currency)])) {
                    $currencyId = $currenciesArr[strtolower($currency)];
                    CampusProgramFee::create([
                        'campus_program_id' => $campusProgramId,
                        'fee_type_id' => $feeType['admission_fee'],
                        'fee_price' => $price,
                        'fee_currency' => $currencyId
                    ]);
                } else {
                    Log::debug("Invalid Currency 2: " . json_encode($record));
                    $sheetError[] = [
                        'message' => "Invalid Currency 2: " . $currency . " " . $record['program_fees'],
                        'record' => $record,
                        'rowNumber' => $rowNumber,
                    ];
                }
            }

            if (!empty(trim($record['app_fee']))) {
                $priceArr = explode(" ", $record['app_fee']);
                $price = $priceArr[0];
                $price = str_replace(",", "", $price);
                $price = floatval($price);

                $currency = str_replace(",", "", $priceArr[1]);
                $currency = str_replace(".", "", $currency);
                $currency = trim($currency);

                if (isset($currenciesArr[strtolower($currency)])) {
                    $currencyId = $currenciesArr[strtolower($currency)];
                    CampusProgramFee::create([
                        'campus_program_id' => $campusProgramId,
                        'fee_type_id' => $feeType['application_fee'],
                        'fee_price' => $price,
                        'fee_currency' => $currencyId
                    ]);
                } else {
                    Log::debug("Invalid Currency 3: " . json_encode($record));
                    $sheetError[] = [
                        'message' => "Invalid Currency 3: " . $currency . " " . $record['program_fees'],
                        'record' => $record,
                        'rowNumber' => $rowNumber,
                    ];
                }
            }

            // Campus Program Intakes
            if (!empty($record['intake'])) {
                $intakes = explode(",", $record['intake']);
                CampusProgramIntake::where('campus_program_id', $campusProgramId)->delete();
                foreach ($intakes as $intake) {
                    if (isset($intakeIdNameArr[strtolower($intake)])) {
                        $intakeId = $intakeIdNameArr[strtolower($intake)];
                        CampusProgramIntake::create([
                            'campus_program_id' => $campusProgramId,
                            'intake_id' => $intakeId
                        ]);
                    } else {
                        Log::debug("Invalid Intake, Does not match with database" . json_encode($record));
                        $sheetError[] = [
                            'record' => $record,
                            'message' => "Invalid Intake. Please provide either of these values: " . implode(", ", array_keys($intakeIdNameArr)),
                            'rowNumber' => $rowNumber
                        ];
                    }
                }
            }
        }

        $this->response = [
            'success' => true,
            'title' => 'Sheet Imported',
            'code' => 'success',
            'message' => 'Sheet imported successfully',
            'sheetError' => $sheetError,
        ];
    }


    public function chunkSize(): int
    {
        return 500;
    }

    public function batchSize(): int
    {
        return 100;
    }
}
