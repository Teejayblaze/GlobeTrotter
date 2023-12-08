<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Input;
use \App\AdminUser;
use \App\AdminUserCredential;
use \App\Http\Traits\TransactionTrait;
use \App\Http\Traits\LoggerTrait;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;

class AdminUserController extends Controller
{
    //
    use TransactionTrait, LoggerTrait;

    public function signup_form()
    {
        return view('admin.signup');
    }


    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'bail|required',
            'lastname' => 'bail|required',
            'email' => 'bail|required|email|unique:admin_users',
            'phone' => 'bail|required',
            'address' => 'bail|required',
            'password' => 'bail|required|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput(Input::except('password'));
        }
        else {

            try {

                 $signup_user= AdminUser::create([
                    'firstname' => $request->firstname,
                    'lastname' => $request->lastname,
                    'staff_id' => 'GT/STF/'.date('Y').'/'.$this->generate_transaction_id(5),
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                if ($signup_user) {

                    $user_cred = new AdminUserCredential();

                    $user_cred->email = $request->email;
                    $user_cred->email_verified_at = Carbon::now();
                    $user_cred->password = bcrypt($request->password);
                    $user_cred->admin_group_id = 5;
                    $user_cred->block = 0;
                    $user_cred->admin_user_id = $signup_user->id;
                    
                    if ( $user_cred->save() ) {
                        
                        $attempt = AdminUserCredential::where([
                            ['email', '=', $request->email],
                        ])->first();

                        if ( \Hash::check($request->password, $attempt->password) ) {
                            $attempt->admingroups = $attempt->admingroup;
                            $attempt->adminroles = $attempt->admingroup->role?$attempt->admingroup->role:[]; 
                            $attempt->userdetail = $attempt->userdetails;
                            \session()->put('user', $attempt);
                            return redirect()->route('admin_dashboard');
                        }   
                    }
                }
            } catch (QueryException $qe) {
                $this->log('Admin::Critical', $qe->getMessage());
            }
        }
    }
}