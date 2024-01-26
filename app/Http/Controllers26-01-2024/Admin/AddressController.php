<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class AddressController extends Controller
{


   public function selectStates($id){
        $states = DB::table('states')->where('country_id','=',$id)->select('id','name')->get();
        return $states;
    }

    public function selectCity($id){
        $cities = DB::table('cities')->where('state_id',$id)->select('id','name')->get();
        return $cities;
    }

    function getCountries(Request $request){
      if(isset($request->name)) return DB::table('countries')->where('name', 'like',  "%{$request->name}%")->select('id', 'name as text')->get();
   }


   function getState(Request $request){
    if(isset($request->name)) return DB::table('states')->where('name', 'like',  "%{$request->name}%")->select('id', 'name as text')->get();
   }
}
