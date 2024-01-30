<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Models\User;
use App\Models\UserShortlistProgram;
use Illuminate\Support\Facades\DB; 

class StudentData implements FromCollection,WithHeadings
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
        $user = UserShortlistProgram::
               rightjoin('users', 'users_shortlist_programs.user_id', '=', 'users.id')
			  ->join('campus_programs', 'users_shortlist_programs.campus_program_id', '=', 'campus_programs.id')
			 ->join('campus', 'campus_programs.campus_id', '=', 'campus.id')
			 ->join('programs', 'campus_programs.program_id', "=", 'programs.id')
			 ->join('universities', 'campus.university_id', '=', 'universities.id')
			
            
            ->get();


            dd($user);

            // UserShortlistProgram::join('campus_programs', 'users_shortlist_programs.campus_program_id', '=', 'campus_programs.id')
			// ->join('programs', 'campus_programs.program_id', '=', 'programs.id')
			// ->join('campus', 'campus_programs.campus_id', '=', 'campus.id')
			// ->join('universities', 'campus.university_id', '=', 'universities.id')
			// ->select('universities.name as university', 'campus.name as campus', 'programs.name as program', 'campus_programs.id as campus_program_id')
			
			// ->get();

        //   return $userApplications;
    }
}
