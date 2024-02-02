<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Models\User;
use App\Models\UserShortlistProgram;
use Illuminate\Support\Facades\DB; 

class StudentData implements FromCollection,WithHeadings,WithChunkReading
{

    public function headings():array{
        return[
            'Id',
            'Name',
            'Email',
            'Phone_No',
            'Passport_No',
            'DOB',
            'University' ,
            'Campus',
            'Program',
            
        ];
    } 
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user = User::
               leftjoin('users_shortlist_programs', 'users_shortlist_programs.user_id', '=', 'users.id')
               
			    ->leftjoin('campus_programs', 'users_shortlist_programs.campus_program_id', '=', 'campus_programs.id')
			   ->leftjoin('campus', 'campus_programs.campus_id', '=', 'campus.id')
			   ->leftjoin('programs', 'campus_programs.program_id', "=", 'programs.id')
			   ->leftjoin('universities', 'campus.university_id', '=', 'universities.id')
			->select(\DB::raw("CONCAT('young_stu','_', users.id) AS StudentId"),\DB::raw("CONCAT(COALESCE(users.name,''), ' ', COALESCE(users.last_name,'')) AS student_name"),'users.email as email','users.personal_number as Phone_No','users.passport as Passport_No','users.dob as DOB','universities.name as university','campus.name as campus','programs.name as program')
            
            ->get();

         


          return $user;

            // UserShortlistProgram::join('campus_programs', 'users_shortlist_programs.campus_program_id', '=', 'campus_programs.id')
			// ->join('programs', 'campus_programs.program_id', '=', 'programs.id')
			// ->join('campus', 'campus_programs.campus_id', '=', 'campus.id')
			// ->join('universities', 'campus.university_id', '=', 'universities.id')
			// ->select('universities.name as university', 'campus.name as campus', 'programs.name as program', 'campus_programs.id as campus_program_id')
			
			// ->get();

        //   return $userApplications;
    }

    public function chunkSize(): int
    {
        return 1000; // Adjust the chunk size based on your requirements
    }
}
