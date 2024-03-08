<?php

namespace App\Http\Controllers\Advertiser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Corporate;
use App\Individual;
use App\UserType;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Input;

class SignupController extends Controller
{
    //
    use \App\Http\Traits\LoggerTrait;
    use \App\Http\Traits\SMSTrait;
    use \App\Http\Traits\RegistrationTrait;
    use \App\Http\Traits\UserProfileTrait;


    private $created_user;
    private $userTypes;

    public function __construct()
    {
        $this->userTypes = [intval(env('CORPORATE_USER_TYPE')), intval(env('INDIVIDUAL_USER_TYPE'))]; // 1 => corporate , 2 => individual.
    }

    // return the form for creating an advertiser.
    public function advertiser_signup_form(string $slug = 'corporate')
    {
        // return view('advertiser.signup');
        $title = "Advertiser Registration";
        return view('advertiser.register', compact("title", "slug"));
    }

    /**
     * validate the user request from the form and create the advertiser in the DB.
     * 
     * Determine if an advertiser is a corporate body or an individual.
     */
    public function create_advertiser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_type' => 'bail|required|filled',
        ]);

        if ( $validator->fails() ) {
            return response()->json(['status' => false, 'errors' => $validator->getMessageBag()->toArray(), 'success' => null]);
        } 
        

        if ( intval($request->user_type) === $this->userTypes[0] ) {
            // handle the saving of corporate advertiser logic.
            return $this->validate_save_corporate_form($request); 
        } 
        else if ( intval($request->user_type) === $this->userTypes[1] ) {
            // handle the saving of individual advertiser logic.
            return $this->validate_save_individual_form($request);
        }

    }

    private function validate_save_corporate_form(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|min:5',
            'website' => 'bail|required|regex:/^((http(s)?)\:\/\/)?(www\.)?[A-za-z\-\_]+(.)?[A-Za-z]+$/i',
            'email' => 'bail|required|email|unique:corporates',
            'phone' => 'bail|required',
            'address' => 'bail|required|filled',
            'rc_number' => 'bail|required|filled|unique:corporates',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->getMessageBag()->toArray(), 'success' => null]);
        }

        $corporate_user = new Corporate();

        $termsandconditions = 1;

        $corporate_user->name  = $request->name;
        $corporate_user->website = $request->website;
        $corporate_user->rc_number = $request->rc_number;
        $corporate_user->address = $request->address;
        $corporate_user->phone = $request->phone;
        $corporate_user->email = $request->email;
        $corporate_user->token = base64_encode($request->email);
        $corporate_user->email_verified = 0;
        $corporate_user->tandc = $termsandconditions;
        $corporate_user->active = 0;

        // dd($request->all());

        try {

            if ( $corporate_user->save() ) {

                $save_corp_user_cred = $this->save_user_credentials($request->email, 'test', $corporate_user->id, $request->user_type, 0);
                
                if ( $save_corp_user_cred ) { 
                    // check if we have saved the corporate user credentials successfully.

                    $this->save_corporate_admin_form($request, $corporate_user->id, $termsandconditions);
                    
                    if ( count($request->auth_staff) ) { 
                        // check if there are any authorized user that we should save.
                        $this->save_corporate_individual_staff($request->auth_staff, $corporate_user->id, $termsandconditions);
                    }

                    return $this->fire_create_user_event($corporate_user); // Fire the save corporate event.
                }   
            }

        } catch (\Illuminate\Database\QueryException $ex) {
            $this->log('Critical', $ex->getMessage()); // Log the exception here.
            // response()->json(['status' => false, 'errors' => $ex->getMessage()->toArray(), 'success' => null]);
        }
    }

    private function save_corporate_individual_staff($staff_recs, $corp_id, $termsandconditions)
    {
        $response = false;

        foreach( $staff_recs as $key => $staff_rec ) {
            $corp_indv = new Individual();
            $corp_indv->firstname = $staff_rec['corp_indv_fname_' . ( $key + 1 )];
            $corp_indv->lastname = $staff_rec['corp_indv_lname_' . ( $key + 1 )];
            // $corp_indv->lastname = $staff_rec['corp_indv_lname_' . ( $key + 1 )];
            $corp_indv->email = $staff_rec['corp_indv_email_' . ( $key + 1 )];
            $corp_indv->token = base64_encode($staff_rec['corp_indv_email_' . ( $key + 1 )]);
            $corp_indv->corp_id = $corp_id;
            $corp_indv->tandc = $termsandconditions;
            $corp_indv->designation = $staff_rec['corp_indv_designation_' . ( $key + 1 )];

            if ( $corp_indv->save() ) { 
                // check if we have saved the individual authorized staff provided by the corporate body.
                
                $email = $staff_rec['corp_indv_email_' . ( $key + 1 )];
                $password = $staff_rec['corp_indv_password_' . ( $key + 1 )];
                
                $save_indv_user_cred = $this->save_user_credentials($email, $password, $corp_indv->id, 2, 0);

                if ($save_indv_user_cred) { 
                    // check if the corporate authorized individual staff credential has been saved successfully.
                    $response = $this->fire_create_user_event($corp_indv); // Fire the save individual event.
                }
            }
        }

        return $response;
    }


    private function save_corporate_admin_form(Request $request, $corp_id, $termsandconditions)
    {
        $response = false;

        $corp_indv = new Individual();
        $corp_indv->firstname = $request->admin_firstname;
        $corp_indv->lastname = $request->admin_lastname;
        $corp_indv->email = $request->admin_email;
        $corp_indv->phone = $request->admin_phone;
        $corp_indv->token = base64_encode($request->admin_email);
        $corp_indv->corp_id = $corp_id;
        $corp_indv->tandc = $termsandconditions;
        $corp_indv->designation = 'Administrator';

        if ( $corp_indv->save() ) { 

            $email = $request->admin_email;
            $password = $request->adminpassword;

            $save_indv_user_cred = $this->save_user_credentials($email, $password, $corp_indv->id, 2, 0, 1);

            if ($save_indv_user_cred) { 
                // check if the corporate authorized individual staff credential has been saved successfully.
                $response = $this->fire_create_user_event($corp_indv); // Fire the save individual event.
            }
        }

        return $response;
    }


    /**
     * Module handles the validation and creation of Individuals Advertiser on the platform.
     * Only individual adevertiser is allowed in this method.
     */

     public function validate_save_individual_form(Request $request)
     {
        $validator = Validator::make($request->all(), [
            'firstname' => 'bail|required|min:5',
            'lastname' => 'bail|required|min:5',
            'email' => 'bail|required|email|unique:individuals',
            'phone' => 'bail|required',
            'address' => 'bail|required|filled',
            // 'bvn' => 'bail|required|filled|unique:individuals|min:11|max:11',
            'password' => 'bail|required|filled|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->getMessageBag()->toArray(), 'success' => null]);
        }

        $individual = new Individual();

        $termsandconditions = 1;

        $individual->firstname = $request->firstname;
        $individual->lastname = $request->lastname;
        $individual->email = $request->email;
        $individual->phone = $request->phone;
        $individual->address = $request->address;
        //$individual->bvn = $request->bvn;
        $individual->token = base64_encode($request->email);
        $individual->tandc = $termsandconditions;
        $individual->active = 0;

        $user_type = $request->user_type;
        $password = $request->password;

        try {
            if ( $individual->save() ) {
                if ( $this->save_user_credentials($request->email, $password, $individual->id, $user_type, 0) ) {
                    return $this->fire_create_user_event($individual);
                }
            }
        } catch(\Illuminate\Database\QueryException $qex) {
            $this->log('Critical', $qex->getMessage()); // Log the exception here.
        }
     }


    public function welcome(string $name = null, string $token = null)
    {
        $title = "Welcome ". $name;
        return view('advertiser.welcome')->with(['name' => urldecode($name), 'title' => $title, 'token' => $token]);
    }


    public function verify_email(string $token = null)
    {
        $advertiser = null;
        $str = 'Corporate body ';
        
        $advertiser = Corporate::where(['token' => $token])->first();
        if ( ! $advertiser ) {
            $advertiser = Individual::where(['token' => $token])->first();
            $str = '';
        }

        if ( $advertiser ) {
            
            $advertiser->email_verified = 1;
            $advertiser->email_verified_at = Carbon::now();
            $advertiser->active = 1;

            if ( $advertiser->save() ) {
                $name = $advertiser->name ? $advertiser->name : $advertiser->lastname;
                $this->log('Info', $str . $name .' has just verified his/her email.');
                return redirect()->route('advertiser_login')->with('flash_message', 'Email verified.');
            }
        }
    }


    public function login_view()
    {
        $title = "Advertiser Login";
        return view('advertiser.login', compact('title'));
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

    public function sendSMS($toNumbers, $content)
    {
        return $this->sendSMSNow($toNumbers, $content);
    }


    private function check_active_account_verification($user_cred)
    {
        $advertiser = null;
        $msg = null;
        $route = '';

        if ($user_cred->user_type_id === $this->userTypes[0]) {

            $advertiser = Corporate::where(['id' => $user_cred->user_id, 'email' => $user_cred->email])->first();
            
            $msg = '(3 times consecutively without payment within 3 months)';

            $route = 'CorporateDashboard';

        } else {

            $advertiser = Individual::where(['id' => $user_cred->user_id, 'email' => $user_cred->email])->first();
            
            $msg = '(2 times consecutively without payment within a month)';

            $route = 'IndividualDashboard';
        
        }
        if ( $advertiser ) {

            if ( ! $advertiser->active ) {
                return redirect()
                    ->back()
                    ->withErrors(['errors' => [ 'loginError' => 'Apologies, it seems you are yet to verify your email.', 'type' => 'Verify me']])
                    ->withInput(Input::except('password'));
            }
    
            if ( $advertiser->blocked ) {
                return redirect()
                    ->back()
                    ->withErrors([
                        'errors' => [ 
                            'loginError' => 'Apologies, You have been deactivated by our system 
                            because of random booking of asset. ' . $msg
                        ]
                    ]);
            }

            $this->set_user_profile($user_cred);
    
            $this->log('Info', 'Authentication successful for user with the email address '. $user_cred->email);
                
            return redirect()->route($route);

        } else {
            Auth::logout();
            return redirect()->back()->withErrors(['errors' => ['Apologies, You don\'t seems to be an advertiser.']]);
        }
    }


    public function usertypes(UserType $userType)
    {
        return response()->json(['status' => true, 'errors' => null, 'success' => UserType::all()]);
    }
}
