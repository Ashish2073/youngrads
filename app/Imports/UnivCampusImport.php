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
    public function collection(Collection $records)
    {
        $univsNameIdArr = University::getNameIdIndexedArray();
       
        $univIdCampusNameArr = Campus::getUnivIdCampusNameArr();
        $countryNameIdArr = Country::getCountryIdNameArr();
        $stateNameIdArr = State::getStateNameIdArr();  
        $cityNameIdArr = City::getCityNameIdArr();
  
        $sheetError = []; 
        $rowNumber = 2;

      
   
        
   
    
        foreach ($records as $record) {
             // University Record

           
            if (!isset($univsNameIdArr[strtolower($record['university'])])) {
                $university = new University;
            } else {
                $university = University::find($univsNameIdArr[strtolower($record['university'])]);
            }

            $university->name = $record['university'];
            $university->type = $record['type'];
            $university->save();
            
            $univsNameIdArr[strtolower($university->name)] = $university->id;
            $universityId = $university->id;

            // Campus Record
            if (!isset($univIdCampusNameArr[$universityId . "__" . strtolower($record['campus'])])) {
                $campus = new Campus;
            } else {
                $campus = Campus::find($univIdCampusNameArr[$universityId . "__" . strtolower($record['campus'])]);
            }

            $campus->name = $record['campus'];
            $campus->university_id = $universityId;

           


             $campus->website = (isset($record['website']) && (!empty($record['website'])))?$record['website']:null;
            $distance = str_replace("km", "", $record['distance']);
            $distance = str_replace("Km", "", $distance);
            $campus->distance = trim($distance);
            $campus->nearest_major_city = $record['nearest_major_city'];
            $campus->latitude = $record['latitude'];
            $campus->longitude = $record['longitude'];
            $campus->save();

            $campusId = $campus->id;
            $univIdCampusNameArr[$universityId . "__" . strtolower($record['campus'])] = $campusId;

            // Campus Address
            // Country
            if (isset($countryNameIdArr[strtolower($record['country'])])) {
                $countryId = $countryNameIdArr[strtolower($record['country'])];
            } else {
                $country = Country::create([
                    'name' => $record['country']
                ]);
                $countryId = $country->id;
                $countryNameIdArr[strtolower($record['country'])] = $countryId;
            }

            // State
            if (isset($stateNameIdArr[$countryId . "__" . strtolower($record['state'])])) {
                $stateId = $stateNameIdArr[$countryId . "__" . strtolower($record['state'])];
            } else {
                $state = State::create([
                    'name' => $record['state'],
                    'country_id' => $countryId
                ]);
                $stateId = $state->id;
                $stateNameIdArr[$countryId . "__" . strtolower($record['state'])] = $stateId;
            }

               
         
            if (isset($cityNameIdArr[$countryId . "__" . $stateId . "__" . strtolower($record['city'])])) {
              
                
               

                $cityId = $cityNameIdArr[$countryId . "__" . $stateId . "__" . strtolower($record['city'])];
             
            } else {
                $city = City::create([
                    'name' => $record['city'],
                    'state_id' => $stateId
                ]);
                $cityId = $city->id;
                $cityNameIdArr[$countryId . "__" . $stateId . "__" . strtolower($record['city'])] = $cityId;
            }

            if (is_null($campus->address_id)) {
                $address = new Address;
            } else {
                $address = Address::find($campus->address_id);
            }

            $address->address = $record['address'];
            $address->country_id = $countryId;
            $address->state_id = $stateId;
            $address->city_id = $cityId;
            $address->post_code=$record['postcode'];
            $address->save();

            $campus->address_id = $address->id;
            $campus->save();

            $rowNumber++;
        }

        return [
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
