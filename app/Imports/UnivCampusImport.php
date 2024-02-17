<?php

namespace App\Imports;

use App\Models\Address;
use App\Models\University;
use App\Models\Campus;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection; 
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

// class UnivCampusImport implements ToCollection, WithHeadingRow, WithChunkReading, WithBatchInserts, ShouldQueue
class UnivCampusImport implements ToCollection, WithHeadingRow
{ 
    public $response;
    public function collection(Collection $records)
    {
        $univsNameIdArr = University::getNameIdIndexedArray();
       
        $univIdCampusNameArr = Campus::getUnivIdCampusNameArr(); 
        $countryNameIdArr = Country::getCountryIdNameArr();
        $stateNameIdArr = State::getStateNameIdArr();  
        $cityNameIdArr = City::getCityNameIdArr();
  
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
               continue; 
            }
            if(!isset($record['campus'])){

                Log::debug('University  not given !' . json_encode($record));
                $sheetError[] = [
                'message' => 'University not given!',
                'record' => $record,
                'rowNumber' => $rowNumber,
            ];
            break;


            }else{
                continue;
            }

            if(!isset($record['country'])){

                Log::debug('Country  not given !' . json_encode($record));
                $sheetError[] = [
                'message' => 'Country not given!',
                'record' => $record,
                'rowNumber' => $rowNumber,
            ];
            break;


            }else{
                continue;
            }

            if(!isset($record['country'])){

                Log::debug('Country  not given !' . json_encode($record));
                $sheetError[] = [
                'message' => 'Country not given!',
                'record' => $record,
                'rowNumber' => $rowNumber,
            ];
            break;


            }else{
                continue;
            }

            if(!isset($record['city'])){

                Log::debug('City  not given !' . json_encode($record));
                $sheetError[] = [
                'message' => 'Country not given!',
                'record' => $record,
                'rowNumber' => $rowNumber,
            ];
            break;


            }else{
                continue;
            }

            if(!isset($record['postcode'])){

                Log::debug('State  not given !' . json_encode($record));
                $sheetError[] = [
                'message' => 'Country not given!',
                'record' => $record,
                'rowNumber' => $rowNumber,
            ];
            break;


            }else{
                continue;
            }

            
            if(!isset($record['state'])){

                Log::debug('State  not given !' . json_encode($record));
                $sheetError[] = [
                'message' => 'Country not given!',
                'record' => $record,
                'rowNumber' => $rowNumber,
            ];
            break;


            }else{
                continue;
            }

              
            if(!isset($record['address'])){

                Log::debug('Address not given !' . json_encode($record));
                $sheetError[] = [
                'message' => 'Address not given!',
                'record' => $record,
                'rowNumber' => $rowNumber,
            ];
            break;


            }else{
                continue;
            }



           }
        
          
       if(empty($sheetError)){
        $rowNumber=1;
       
        foreach ($records as $record) {
             // University Record
             $rowNumber++;



            if(isset($record['university'])){
            if (!isset($univsNameIdArr[trim(strtolower($record['university']))])) {
                $university = new University;
            } else {
                $university = University::find($univsNameIdArr[trim(strtolower($record['university']))]);
            }

            $university->name = $record['university'];
            $university->type = $record['type'];
            $university->save();
            
            $univsNameIdArr[strtolower($university->name)] = $university->id;
            $universityId = $university->id;
            }else{
                Log::debug('University  not given !' . json_encode($record));
                $sheetError[] = [
                'message' => 'University not given!',
                'record' => $record,
                'rowNumber' => $rowNumber,
            ];
            continue;

        }
        
            // Campus Record
            if(isset($universityId) && isset($record['campus'])){
            if (!isset($univIdCampusNameArr[$universityId . "__" . trim(strtolower($record['campus']))])) {
                $campus = new Campus;
            } else {
                $campus = Campus::find($univIdCampusNameArr[$universityId . "__" . trim(strtolower($record['campus']))]);
            }

            $campus->name = $record['campus'];
            $campus->university_id = $universityId;

           


             $campus->website = (isset($record['website']) && (!empty($record['website'])))?$record['website']:null;
            $distance = str_replace("km", "", $record['distance']);
            $distance = str_replace("Km", "", $distance);
            $campus->distance = trim($distance);
            $campus->nearest_major_city =(isset($record['nearest_major_city']) && (!empty($record['nearest_major_city'])))?$record['nearest_major_city']:null;
            $campus->latitude = (isset($record['latitude']) && (!empty($record['latitude'])))?$record['latitude']:null;
            $campus->longitude = (isset($record['longitude']) && (!empty($record['longitude'])))?$record['longitude']:null;;
            $campus->save();

            $campusId = $campus->id;
            $univIdCampusNameArr[$universityId . "__" . trim(strtolower($record['campus']))] = $campusId;
        }else{
            Log::debug('campus  not given!' . json_encode($record));
            $sheetError[] = [
            'message' => 'Campus not given!',
            'record' => $record,
            'rowNumber' => $rowNumber,
        ];
        continue;

    }
            // Campus Address
            // Country
            if(isset($record['country'])){
            if (isset($countryNameIdArr[trim(strtolower($record['country']))])) {
                $countryId = $countryNameIdArr[trim(strtolower($record['country']))];
            } else {
                $country = Country::create([
                    'name' => $record['country']
                ]);
                $countryId = $country->id;
                $countryNameIdArr[trim(strtolower($record['country']))] = $countryId;
            }
             }else{
                Log::debug('country  not given!' . json_encode($record));
                $sheetError[] = [
                'message' => 'country not given!',
                'record' => $record,
                'rowNumber' => $rowNumber,
            ];
            continue;

        }
            // State
            if(isset($countryId) && isset($record['state'])){
            if (isset($stateNameIdArr[$countryId . "__" . trim(strtolower($record['state']))])) {
                $stateId = $stateNameIdArr[$countryId . "__" . trim(strtolower($record['state']))];
            } else {
                $state = State::create([
                    'name' => $record['state'],
                    'country_id' => $countryId
                ]);
                $stateId = $state->id;
                $stateNameIdArr[$countryId . "__" . trim(strtolower($record['state']))] = $stateId;
            }

        }else{
            Log::debug('University  not given!' . json_encode($record));
            $sheetError[] = [
            'message' => 'state not given!',
            'record' => $record,
            'rowNumber' => $rowNumber,
        ];
        continue;

    }   
         ////city
         if(isset($stateId) && isset($record['city'])){
            if (isset($cityNameIdArr[$countryId . "__" . $stateId . "__" . trim(strtolower($record['city']))])) {
              
                
               

                $cityId = $cityNameIdArr[$countryId . "__" . $stateId . "__" . trim(strtolower($record['city']))];
             
            } else {
                $city = City::create([
                    'name' => $record['city'],
                    'state_id' => $stateId
                ]);
                $cityId = $city->id;
                $cityNameIdArr[$countryId . "__" . $stateId . "__" . trim(strtolower($record['city']))] = $cityId;
            }
        }else{
            Log::debug('city  not given!' . json_encode($record));
            $sheetError[] = [
            'message' => 'City not given!',
            'record' => $record,
            'rowNumber' => $rowNumber,
        ];
        continue;

    }

        ////addressss///////
        if(isset($campus) && isset($stateId) && isset($countryId) && isset($cityId)){
            if (is_null($campus->address_id)) {
                $address = new Address;
            } else {
                $address = Address::find($campus->address_id);
            }

            $address->address = (isset($record['address']) && !empty($record['address']))?$record['address']:null;
            $address->country_id = $countryId;
            $address->state_id = $stateId;
            $address->city_id = $cityId;
            $address->post_code=(isset($record['postcode']) && !empty($record['postcode']))?$record['postcode']:null;
            $address->save();

            $campus->address_id = $address->id;
            $campus->save();
        }else{
            Log::debug('address  not given!' . json_encode($record));
            $sheetError[] = [
            'message' => 'state ,country,city, of campus not given !' . $record['address'],
            'record' => $record,
            'rowNumber' => $rowNumber,
        ];
        continue;

    }
            $rowNumber++;
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
