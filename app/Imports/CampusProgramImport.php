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


        foreach($records as $record){
            if(!isset($record['university'])){
                Log::debug('University  not given !' . json_encode($record));
                $sheetError[] = [
                'message' => 'University not given!',
                'record' => $record,
                'rowNumber' => $rowNumber,
            ];
            break;
            

            }else{

                if (!isset($univsNameIdArr[trim(strtolower($record['university']))])) {
                    Log::debug('University does not exists!' . json_encode($record));
                    $sheetError[] = [
                        'message' => 'University does not exists!' . json_encode($record),
                        'record' => $record,
                        'rowNumber' => $rowNumber,
                    ];
                    break;

                }else{

                    continue;

                }



             
            }
            if(!isset($record['campus'])){

                Log::debug('Campus  not given !' . json_encode($record));
                $sheetError[] = [
                'message' => 'Campus not given!',
                'record' => $record,
                'rowNumber' => $rowNumber,
            ];
            break;


            }else{

                if (!isset($univIdCampusNameArr[$univId . "__" . trim(strtolower($record['campus']))])) {
                    Log::debug('Campus does not exists!'.$record['campus'] . json_encode($record));
                    $sheetError[] = [
                        'message' => 'Campus does not exists!'.$record['campus'],
                        'record' => $record,
                        'rowNumber' => $rowNumber,
                    ];
                    break;

                
            }else{
                continue;
            }


        }

            if(!isset($record['level'])){

                Log::debug('level  not given !' . json_encode($record));
                $sheetError[] = [
                'message' => 'level not given!',
                'record' => $record,
                'rowNumber' => $rowNumber,
            ];
            break;


            }else{

                if (!isset($programLevelIdArr[trim(strtolower($record['level']))])) {
                            
            
              
                    Log::debug('Program Level does not exists!' . json_encode($record));
                    $sheetError[] = [
                        'message' => 'Program Level does not exists!'.$record['level'],
                        'record' => $record,
                        'rowNumber' => $rowNumber,
                    ];

                    break;

                }else{
                    continue;
                }


              
            }

            if(!isset($record['study_area'])){

                Log::debug('study area  not given !' . json_encode($record));
                $sheetError[] = [
                'message' => 'Study area  not given!',
                'record' => $record,
                'rowNumber' => $rowNumber,
            ];
            break;


            }else{
                continue;
            }

            if(!isset($record['sub_study_area'])){

                Log::debug('Sub Study Area   not given !' . json_encode($record));
                $sheetError[] = [
                'message' => 'Country not given!',
                'record' => $record,
                'rowNumber' => $rowNumber,
            ];
            break;


            }else{
                continue;
            }

            if(!isset($record['currency_type'])){

                Log::debug('Currency type  not given !' . json_encode($record));
                $sheetError[] = [
                'message' => 'Currency type  not given!',
                'record' => $record,
                'rowNumber' => $rowNumber,
            ];
            break;


            }else{

                if (!isset($currenciesArr[trim(strtolower($record['currency_type']))])) {

                    Log::debug('Currency type not match  given !' . json_encode($record));
                    $sheetError[] = [
                    'message' => 'Currency type not match!',
                    'record' => $record,
                    'rowNumber' => $rowNumber,
                ];
                break;



                }else{
                    continue;
                }
              
            }

            
            if(!isset($record['app_fee'])){

                Log::debug('Application fees  not given !' . json_encode($record));
                $sheetError[] = [
                'message' => 'Application Tuition fees  not given!',
                'record' => $record,
                'rowNumber' => $rowNumber,
            ];
            break;


            }else{

                if(!is_numeric($record['app_fee'])){
                    Log::debug('Application  fesss  not Numeric !' . json_encode($record));
                    $sheetError[] = [
                    'message' => 'Application fees  not Numeric!',
                    'record' => $record,
                    'rowNumber' => $rowNumber,
                ];
                break;

                }else{
                    continue;
                }




            
            }



            if(!isset($record['annual_tuition_fee'])){

                Log::debug('Annual Tuition fesss  not given !' . json_encode($record));
                $sheetError[] = [
                'message' => 'Annual Tuition fesss  not given!',
                'record' => $record,
                'rowNumber' => $rowNumber,
            ];
            break;


            }else{

                if(!is_numeric($record['annual_tuition_fee'])){
                    Log::debug('Annual Tuition fees  not Numeric !' . json_encode($record));
                    $sheetError[] = [
                    'message' => 'Annual Tuition fees  not Numeric!',
                    'record' => $record,
                    'rowNumber' => $rowNumber,
                ];
                break;

                }else{
                    continue;
                }




            
            }

          ////annual_tuition_fee program_fee


          if(!isset($record['program_fee'])){

            Log::debug('Program fees  not given !' . json_encode($record));
            $sheetError[] = [
            'message' => 'Program Tuition fees  not given!',
            'record' => $record,
            'rowNumber' => $rowNumber,
        ];
        break;


        }else{

            if(!is_numeric($record['program_fee'])){
                Log::debug('Program fees  not Numeric !' . json_encode($record));
                $sheetError[] = [
                'message' => 'Program fees  not Numeric!',
                'record' => $record,
                'rowNumber' => $rowNumber,
            ];
            break;

            }else{
                continue;
            }




        
        }



            





              
          



           }
        



        
        if(empty($sheetError)) {
            $rowNumber=1;
        foreach ($records as $record) {
            
            $rowNumber++;

           
            // if (empty($record['university'])) {
            //     dd($record);
            //     continue;
            // }
            // University ID

            if(isset($record['university'])){
            if (!isset($univsNameIdArr[trim(strtolower($record['university']))])) {
                Log::debug('University does not exists!' . json_encode($record));
                $sheetError[] = [
                    'message' => 'University does not exists!' . json_encode($record),
                    'record' => $record,
                    'rowNumber' => $rowNumber,
                ];
                continue;
            }else{
                $univId = $univsNameIdArr[trim(strtolower($record['university']))];
            }
           
            }


            // Campus ID
             if(isset($record['campus'])){
            if (!isset($univIdCampusNameArr[$univId . "__" . trim(strtolower($record['campus']))])) {
                Log::debug('Campus does not exists!'.$record['campus'] . json_encode($record));
                $sheetError[] = [
                    'message' => 'Campus does not exists!'.$record['campus'],
                    'record' => $record,
                    'rowNumber' => $rowNumber,
                ];
                continue;
            }else{
                $campusId = $univIdCampusNameArr[$univId . "__" . trim(strtolower($record['campus']))];
            }
           
           }
            // Program Level ID

            if(isset($record['level'])){
            if (!isset($programLevelIdArr[trim(strtolower($record['level']))])) {
                            
            
              
                Log::debug('Program Level does not exists!' . json_encode($record));
                $sheetError[] = [
                    'message' => 'Program Level does not exists!'.$record['level'],
                    'record' => $record,
                    'rowNumber' => $rowNumber,
                ];
                continue;
            } else {
                $programLevelId = $programLevelIdArr[trim(strtolower($record['level']))];
            }
          }
            // Study Area ID
            if(isset($record['study_area'])){
             
            if (!isset($studyAreaNameIdArr[trim(strtolower($record['study_area']))])) {
                $studyArea = new Study;
                $studyArea->name = $record['study_area'];
                $studyArea->slug = Str::slug($record['study_area'], "-");
                $studyArea->save();
                $studyAreaId = $studyArea->id;
            } else {
                $studyAreaId = $studyAreaNameIdArr[trim(strtolower($record['study_area']))];
            }
             }
            // Program
            if(isset($record['program'])){
            if (!isset($programNameIdArr[trim(strtolower($record['program']))])) {
                $program = new Program;
            } else {
                $program = Program::find($programNameIdArr[trim(strtolower($record['program']))]);
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
        }


            if(isset($record['sub_study_area']) && isset($programStudyAreaIdArr)){
            if (!empty($record['sub_study_area'])) {
                $subStudyArea = explode(",", $record['sub_study_area']);
                foreach ($subStudyArea as $area) {
                    if (isset($subStudyNameIdArr[$studyAreaId . "__" . trim(strtolower($area))])) {
                        $subStudyAreaId = $subStudyNameIdArr[$studyAreaId . "__" . trim(strtolower($area))];
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


        }

            // Campus Program
            if(isset($campusId) && isset($programId)){
            if (!isset($campusProgramsArr[$campusId . "__" . $programId])) {
                $campusProgram = new CampusProgram;
            } else {
                $campusProgram = CampusProgram::find($campusProgramsArr[$campusId . "__" . $programId]);
            }


            $campusProgram->campus_id = $campusId;
            $campusProgram->program_id = $programId;
            $campusProgram->entry_requirment = ($record['entry_requirements'])??null;
            $campusProgram->campus_program_duration = $duration;
            $campusProgram->website = ($record['website']) ?? null;
            $campusProgram->save();

            $campusProgramId = $campusProgram->id;

        }

            // Campus Program Tests
            if(isset( $campusProgramId)){
            CampusProgramTest::where('campus_program_id', $campusProgramId)->delete();

           if(isset($record['entrance_exam_name']) && !empty($record['entrance_exam_name'])){

            $entrenceExamAllName=explode(',',$record['entrance_exam_name']);
            $entranceExamMinNumber=explode(',',$record['entrance_score_min']);
            $entranceExamMaxNumber=explode(',',$record['entrance_score_max']);
            $entrenceExamAllTestScore=explode(',',  $record['required_score_for_enterence']);
            $testValues=[];
              $testBoolean=true;
              foreach($entrenceExamAllName as $k=>$testName){
                  $testValues[$k]['test_name']=$testName;  

                  if(!is_numeric($entranceExamMinNumber[$k])){
                   Log::debug(json_encode($testName).'Test Score Minimum Validation!' . json_encode($testName));
                   $sheetError[] = [
                    'message' => $testName." ".'Test  Minimum Score Not Numeric ! !'."Given Value Is" .$entranceExamMinNumber[$k],
                    'record' => $record,
                    'rowNumber' => $rowNumber,
                 ];
                 $testBoolean=false;
                   continue;
                  
                }else{
                    $testValues[$k]['min'] =(isset($entranceExamMinNumber[$k]) && !empty($entranceExamMinNumber[$k])? (int)$entranceExamMinNumber[$k]:null);   
                   
                }

              if(!is_numeric($entranceExamMaxNumber[$k])){
                Log::debug(json_encode($testName).'!Test Score Maxmium Validation!' . json_encode($testName));
                $sheetError[] = [
                    'message' => $testName." ".'Test  Maximum Score Not Numeric !' ."Given Value Is". $entranceExamMaxNumber[$k],
                    'record' => $record,
                    'rowNumber' => $rowNumber,
                ];
                $testBoolean=false;
                continue;
            }else{
                $testValues[$k]['max'] =(isset($entranceExamMaxNumber[$k]) && !empty($entranceExamMaxNumber[$k])? (int)$entranceExamMaxNumber[$k]:null);   
               
           
            }

            }
           
         
           
           
        

            if($testBoolean){
            $TestInput=Test::upsert($testValues,'test_name', ['min', 'max']);
            }

           

            $TestAll=Test::whereIn('test_name',$entrenceExamAllName)->pluck('id')->toArray();

          



          

            $TestSocreNlt=(isset($record['required_nlt_score_for_enterence']) && !empty($record['required_nlt_score_for_enterence'])) ? $record['required_nlt_score_for_enterence']:null;
             
            if(isset($record['required_score_for_enterence']) && !empty($record['required_score_for_enterence'])){
                $entrenceExamAllTestScore=explode(',', $record['required_score_for_enterence']);
                
            }else{
                $entrenceExamAllTestScore=null;
            }

              
            

          



            




            $Campusrecords = [];
            $testScoreBoolean=true;


            foreach ($TestAll as $k=>$testId) {


                if(!is_numeric($entrenceExamAllTestScore[$k])){
              
                    Log::debug(json_encode($testName).'Test Score Validation!' . json_encode($testName));
                    $sheetError[] = [
                        'message' => $testName." ".'Test Score Not Numeric!'."Given Value Is" .$entrenceExamAllTestScore[$k],
                        'record' => $record,
                        'rowNumber' => $rowNumber,
                    ];
                    $testScoreBoolean=false;
                    continue;
                  

                }else{
                    $Campusrecords[] = [
                        'campus_program_id' => $campusProgramId,
                        'test_id' => (isset($testId) && !empty($testId)?$testId:null),
                        'score' =>  (isset($entrenceExamAllTestScore[$k]) && !empty($entrenceExamAllTestScore[$k])?$entrenceExamAllTestScore[$k]:null),
                        'nlt_score' => $TestSocreNlt,
                        'show_in_front' => 1,
                    ];

                }


              
            }
      
            if($testScoreBoolean){
                CampusProgramTest::insert($Campusrecords);
            }
           


        


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

            if (!empty(($record['annual_tuition_fee'])) && isset($record['annual_tuition_fee'])) {
                $price =$record['annual_tuition_fee'];
                $currencyType=$record['currency_type'];

                
               

                if (isset($currenciesArr[trim(strtolower($currencyType))])) {
                    $currencyId = $currenciesArr[trim(strtolower($currencyType))];
                   
                    CampusProgramFee::create([
                        'campus_program_id' => $campusProgramId,
                        'fee_type_id' => $feeType['tuition_fee'],
                        'fee_price' => $price,
                        'fee_currency' => $currencyId
                    ]);
                } else {
                    Log::debug("Invalid Currency 1: " . json_encode($record));
                    $sheetError[] = [
                        'message' => "Invalid Currency 1: " . $currencyType . " " . $record['annual_tuition_fee'],
                        'record' => $record,
                        'rowNumber' => $rowNumber,
                    ];
                    continue;
                }
            }

            if (!empty(($record['program_fee'])) && isset($record['program_fee'])) {

                $price= $record['program_fee'];
                $currencyType=$record['currency_type'];
             
;

                if (isset($currenciesArr[trim(strtolower($currencyType))])) {
                    $currencyId = $currenciesArr[trim(strtolower($currencyType))];
                    CampusProgramFee::create([
                        'campus_program_id' => $campusProgramId,
                        'fee_type_id' => $feeType['admission_fee'],
                        'fee_price' => $price,
                        'fee_currency' => $currencyId
                    ]);
                } else {
                    Log::debug("Invalid Currency 2: " . json_encode($record));
                    $sheetError[] = [
                        'message' => "Invalid Currency 2: " . $currencyType . " " . $record['program_fee'],
                        'record' => $record,
                        'rowNumber' => $rowNumber,
                    ];
                    continue;
                }
            }

            if (!empty(($record['app_fee'])) && isset($record['app_fee'])) {
                $price = $record['app_fee'];
                $currencyType=$record['currency_type'];

            

                if (isset($currenciesArr[trim(strtolower($currencyType))])) {
                    $currencyId = $currenciesArr[trim(strtolower($currencyType))];
                    CampusProgramFee::create([
                        'campus_program_id' => $campusProgramId,
                        'fee_type_id' => $feeType['application_fee'],
                        'fee_price' => $price,
                        'fee_currency' => $currencyId
                    ]);
                } else {
                    Log::debug("Invalid Currency 3: " . json_encode($record));
                    $sheetError[] = [
                        'message' => "Invalid Currency 3: " . $currencyType . " " . $record['app_fee'],
                        'record' => $record,
                        'rowNumber' => $rowNumber,
                    ];
                    continue;
                }
            }

            // Campus Program Intakes
            if (!empty($record['intake'])) {
                $intakes = explode(",", $record['intake']);
                CampusProgramIntake::where('campus_program_id', $campusProgramId)->delete();
                foreach ($intakes as $intake) {
                    if (isset($intakeIdNameArr[trim(strtolower($intake))])) {
                        $intakeId = $intakeIdNameArr[trim(strtolower($intake))];
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
                        continue;
                    }
                }
            }
        }
        }
        
    }

}
  
        if(empty($sheetError)){
           
            $this->response = [
                'success' => true,
                'title' => 'Sheet Imported',
                'code' => 'success',
                 'message' => 'Sheet imported successfully',
                
            ];

            return $this->response;

        }else{ 
            
          
            $this->response = [
                'success' => false,
                'title' => 'Sheet Not Imported',
                'code' => 'fail',
                'message' => 'Sheet Not Imported',
                'sheetError' => $sheetError,
            ];
           
          
            return $this->response;
           
            
        }
        
      
 
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
