<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Input;
use App\Http\Controllers\Controller;
use \App\AdminUserCredential;

class AdminUserCredentialController extends Controller
{
    //

    public function login_form()
    {
        $title = "Admin Login";
        return view('admin.login', compact('title'));
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'bail|required|email',
            'password' => 'bail|required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput(Input::except('password'));
        }
        else {
            return $this->authenticate($request);
        }
    }

    private function authenticate($request)
    {
        $attempt = AdminUserCredential::where([
            ['email', '=', $request->email],
        ])->first();

        if ( $attempt ) {

            if ( \Hash::check($request->password, $attempt->password) ) {
                $attempt->admingroups = $attempt->admingroup;
                $attempt->adminroles = $attempt->admingroup->role; 
                $attempt->userdetail = $attempt->userdetails;
                \session()->put('user', $attempt); 
                return redirect()->route('admin_dashboard');
            } else {
                return redirect()->back()->withErrors(['errors' => ['The provided password or email is not valid in our record.']])->withInput(Input::except('password'));
            }

        } else {
            return redirect()->back()->withErrors(['errors' => ['The provided email or password is not valid in our record.']])->withInput(Input::except('password'));
        }
    }
}
