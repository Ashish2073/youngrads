<?php

namespace App\Rules;

use Closure;
use App\Models\UserApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\ValidationRule;

class UserApplicationAuthentication implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

     public $value;

     public function __construct($data)
     {
         $this->value=$data;
     }


    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
       $countApplication= UserApplication::where('user_id', '=', Auth::id())->count();
       if( $countApplication>=$this->value){
       $fail("You have only ".$this->value."  limit for apply application!!!");

       }
    }
}
