<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
class StudyLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $studyLevels = [
           ['name'=>'Grade 10', 'sequence'=>7],
           ['name'=>'Grade 12', 'sequence'=>6],
           ['name'=>'UG Diploma', 'sequence'=>5],
           ['name'=>'UG Degree', 'sequence'=>4],
           ['name'=>'PG Diploma', 'sequence'=>3],
           ['name'=>'PG Degree', 'sequence'=>2],
           ['name'=>'PhD', 'sequence'=>1],
           ['name'=>'Other', 'sequence'=>0],
         ];

         foreach($studyLevels as $studyLevel){
            DB::table('study_levels')->insert([
                   'name' => $studyLevel['name'],
                   'sequence' => $studyLevel['sequence'],
                   'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
         }
    }
}
