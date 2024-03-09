<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Models\UserApplication;
use Illuminate\Support\Facades\DB;

class StudentApplication implements FromCollection,WithHeadings,WithChunkReading
{

    public function headings():array{
        return[
            'Student',
            'moderarorId',
            'ApplicationId',
            'University',
            'Campus',
            'Program',
            'Intake',
            'Status' ,
            'AppliedDate'
        ];
    } 





    /** 'users.name as first', 'users.last_name as last_name'
    * @return \Illuminate\Support\Collection
    */ 
    public function collection()
    {
        $userApplications = UserApplication::join('users', 'users_applications.user_id', '=', 'users.id')
		->leftJoin('admins','users.moderator_id','=','admins.id')	
        ->join('campus_programs', 'users_applications.campus_program_id', '=', 'campus_programs.id')
			->join('intakes', 'users_applications.intake_id', '=', 'intakes.id')
			->join('campus', 'campus_programs.campus_id', '=', 'campus.id')
			->join('programs', 'campus_programs.program_id', "=", 'programs.id')
			->join('universities', 'campus.university_id', '=', 'universities.id')
			->select(\DB::raw("CONCAT(COALESCE(users.name,''), ' ', COALESCE(users.last_name,'')) AS student_name"),'admins.username as moderator_id','users_applications.application_number','universities.name as university','campus.name as campus','programs.name as program',\DB::raw("CONCAT(intakes.name, '-', year) AS intake"),'status', \DB::raw("DATE_FORMAT(users_applications.created_at ,'%d/%b/%Y') AS date")) 
			->get();

          return $userApplications;
    }


    public function chunkSize(): int
    {
        return 1000; // Adjust the chunk size based on your requirements
    }
}
