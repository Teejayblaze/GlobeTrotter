<?php

namespace App\Http\Traits;
use \App\Corporate;
use \App\Individual;
use \App\Operator;
use Illuminate\Support\Facades\Auth;

trait UserProfileTrait
{
    //    
   
    public function set_user_profile($user)
    {
        // Retrive Corporate User Info
        if ( $user->user_type_id === intval(env('CORPORATE_USER_TYPE')) && $user->operator === 0 ) {

            $corporate = Corporate::where([['id', '=', $user->user_id], ['email', '=', $user->email]])->first();
            
            if ($corporate) {
                $corporate->user_type_id = $user->user_type_id;
                $corporate->user_id = $user->user_id;
                $corporate->operator = $user->operator;
                $corporate->admin = $user->admin;
                if (!\session()->has('corporate')) session()->put('corporate', $corporate);
            } 
        }
        else if ( $user->user_type_id === intval(env('INDIVIDUAL_USER_TYPE')) && $user->operator === 0 ) {
            
            $individual = Individual::where([['id', '=', $user->user_id], ['email', '=', $user->email]])->first();

            if ($individual) {

                if ( $individual->corp_id ) {
                    
                    $corporate = Corporate::where([['id', '=', $individual->corp_id]])->first();
                    
                    $individual->work_with = $corporate;
                }

                $individual->admin = $user->admin;
                $individual->user_type_id = $user->user_type_id;
                $individual->user_id = $user->user_id;
                $individual->operator = $user->operator;
                if (!\session()->has('individual')) session()->put('individual', $individual);
            }
        }
        else if (($user->user_type_id === 1 || $user->user_type_id === 2) && $user->operator === 1 ) {
            if ($user->user_type_id === 1)
                $operator = Operator::where([['id', '=', $user->user_id], ['email', '=', $user->email]])->first();
            else {
                $operator = Individual::where([['id', '=', $user->user_id], ['email', '=', $user->email]])->first();
                if ($operator) {
                    $corporate = Operator::where('id', $operator->corp_id)->first();
                    $corporate->name = $corporate->corporate_name;
                    $operator->work_with = $corporate;
                }
            }
                

            
            if ($operator) {
                $operator->user_type_id = $user->user_type_id;
                $operator->user_id = $user->user_id;
                $operator->operator = $user->operator;
                $operator->admin = $user->admin;
                if (!\session()->has('operator')) session()->put('operator', $operator);
            }
        }
    }


    public function get_operator_by_id(int $operatorId = 0) {
        return Operator::find($operatorId);
    }


    public function get_user_profile(string $key) {
        return \session()->get($key);
    }
}
