<?php

namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\UserCredential;
use App\Individual;
use App\Providers\UserRegistered;
use App\AssetType;
use App\Asset;
use App\AssetImage;
use App\Keywords;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Http\Traits\GenericThirdPartyRequestTrait;
use App\Operator;
use App\Http\Traits\RegistrationTrait;

class SignupController extends Controller
{
    //
    use \App\Http\Traits\LoggerTrait;
    use \App\Http\Traits\SMSTrait;
    use \App\Http\Traits\UserProfileTrait;
    use GenericThirdPartyRequestTrait, RegistrationTrait;

    private $created_user;
    private $userTypes;


    public function __construct()
    {
        $this->userTypes = [1,2]; // 1 => corporate , 2 => individual.
    }



    // return the form for creating an operator.
    public function operator_signup_form()
    {
        return view('operator.search');
    }



    public function search_operator(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'oaan_number' => 'bail|required|min:10'
        ]);

        if ( $validator->fails() ) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        // TODO: request for the operator's information from OAAN portal.
        $jsonResponse = json_decode($this->sendRequestNow(config('app.oaan_api_url') . "/verify/" . base64_encode($request->oaan_number)));

        if ( $jsonResponse->status ) {
            $request->session()->put('op_verify_det', json_encode($jsonResponse));
            return redirect('/operator/verification');
        }
        else {
            return redirect()
                    ->back()
                    ->withErrors(['errors' => [$jsonResponse->errors]])
                    ->withInput();
        }
    }



    public function verify_operator(Request $request)
    {
        $title = "Sign Up As An " . config("app.name") . " Member";
        return view('operator.signup', compact('title'))->with('op_verify_det', json_decode($request->session()->get('op_verify_det')));
    }



    public function create_operator(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'password' => 'bail|required|confirmed',
            'email' => 'bail|required|email|unique:operators',
            'phone' => 'bail|required',
        ]);

        if ( $validator->fails() ) {
            return redirect()->back()->withErrors($validator)->withInput(Input::only('phone'));
        }


        if(Operator::where('oaan_number', $request->oaan_number)->first()) {
            return redirect()->back()->withErrors(['errors' => ['You are already a registered member, kindly login to your dashboard.']])->withInput();
        }

        $jsonResponse = json_decode($this->sendRequestNow(config('app.oaan_api_url') . '/set_status/'  . base64_encode($request->oaan_number)));

        if(!$jsonResponse->status) {
            return redirect()->back()->withErrors(['errors' => ['Apologies, We are currently unable to set the status of your verification. Kindly try again soon.']])->withInput();
        }

        $operator = new Operator();
        $operator->corporate_name = $request->corporate_name;
        $operator->rc_number = $request->rc_number;
        $operator->oaan_number = $request->oaan_number;
        $operator->email = $request->email;
        $operator->token = base64_encode($request->email);
        $operator->verified = 1;
        $operator->phone = $request->phone;

        try {

            \session()->forget('op_verify_det');

            if ( $operator->save() ) {
                $saved_cred = $this->save_user_credentials($request->email, $request->password, $operator->id, 1, 1, 1);
                if ( $saved_cred ) {
                    $prep = $this->fire_create_user_event($operator, 'operator.mails.signup', 'operator');
                    $prep = json_decode($prep->getContent());
                    if ($prep->status) {
                        return redirect()->route('operator_welcome', ['name' => $request->corporate_name, 'token' => base64_encode($request->email)]);
                    }
                }
            }

        } catch (\Illuminate\Database\QueryException $qe) {
            $this->log('Critical', $qe->getMessage());
        }        

    }



    public function verify_email(string $token = null)
    {
        $operator = Operator::where(['token' => $token])->first();
    
        if ( $operator ) {
            
            $operator->email_verified = 1;
            $operator->email_verified_at = Carbon::now();
            $operator->active = 1;

            if ( $operator->save() ) {
                $this->log('Info', $operator->corporate_name .' has just verified his/her email.');
                return redirect()->route('operator_login')->with('flash_message', 'Email verified.');
            }
        }
    }



    public function login_view()
    {
        return view('operator.login');
    }



    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'bail|required|email',
            'password' => 'bail|required',
        ]);

        if ( $validator->fails() ) {
            return redirect()->back()->withErrors($validator)->withInput(Input::except('password'));
        }

        return $this->authenticate($request);
    }



    private function authenticate(Request $request)
    {   
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->back()->withErrors(['errors' => [ 'loginError' => 'The provided credentials are not valid in our record.']]);
        } 
        else {

            $user = Auth::user();
            return $this->check_active_account_verification($user);
        }
        
    }


    private function check_active_account_verification($user_cred)
    {
        $operator = null;
        if (in_array($user_cred->user_type_id, $this->userTypes)) {
            if ($user_cred->admin) $operator = Operator::where([['id', '=', $user_cred->user_id], ['email', '=', $user_cred->email]])->first();
            else {
                $operator = Individual::where([['email', '=', $user_cred->email], ['operator', '=', 1]])->first();
                if ($operator) $user_cred->operator = $operator->operator;
            } 
        }
        
        if ($operator) {
            if ( ! $operator->active ) {
                return redirect()
                    ->back()
                    ->withErrors(['errors' => ['loginError' => 'Apologies, it seems you are yet to verify your mail.']]);
            }

            if ($operator->blocked) {
                return redirect()
                    ->back()
                    ->withErrors(['errors' => ['loginError' => 'Apologies, it seems you have been blocked by the platform, kindly contact '. config('app.name') .' administrator.']]);
            }
        }
        else {
            Auth::logout();
            return redirect()->back()->withErrors(['errors' => [ 'loginError' => 'Apologies, You are not an Operator on our Platform.']]);
        }

        $this->set_user_profile($user_cred);

        $this->log('Info', 'Authentication successful for user with the email address '. $user_cred->email);
            
        return redirect()->route('operator_dashboard');
    }


    public function welcome(string $name = null, string $token = null)
    {
        return view('operator.welcome')->with(['name' => urldecode($name), 'token' => $token]);
    }
}
