<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\AssetType;
use \App\Asset;
use \App\Transaction;
use \App\UserCredential;
use \App\Http\Traits\UserProfileTrait;
use \App\Http\Traits\GenericThirdPartyRequestTrait;
use \App\Http\Traits\LoggerTrait;
use Illuminate\Support\Facades\Validator;
use Mail;

class WebsiteController extends Controller
{
    use UserProfileTrait, GenericThirdPartyRequestTrait, LoggerTrait;
    //

    public function home()
    {
        $title = "Digital Solution for Outdoor Advertising";
        $user = $this->get_user_profile('individual');
        $assetCount = Asset::count('id');
        $transactionCount = array_column(Transaction::select('amount')->get()->toArray(), 'amount');
        $transactionSum = 0;
        if(count($transactionCount)) {
            $transactionSum = array_reduce($transactionCount, function($oldValue, $newValue) { return $oldValue += floatval(str_replace(',', '', $newValue)); }, 0);
        }
        $advertiserCount = UserCredential::where(['user_type_id' => 1])->orWhere(['user_type_id' => 2])->count('id');
        return view('main-site.home', [
            'asset_types' => AssetType::all(), 
            'asset_count' => $assetCount, 
            't_sum' => $transactionSum,
            'advertiser_count' => $advertiserCount,
            'user' => $user, 
            'title' => $title
        ]);
    }

    public function landing()
    {
        $title = "Digital Solution for Outdoor Advertising";
        $user = $this->get_user_profile('individual');
        $assetCount = Asset::count('id');
        $transactionCount = array_column(Transaction::select('amount')->get()->toArray(), 'amount');
        $transactionSum = 0;
        if(count($transactionCount)) {
            $transactionSum = array_reduce($transactionCount, function($oldValue, $newValue) { return $oldValue += floatval(str_replace(',', '', $newValue)); }, 0);
        }
        $advertiserCount = UserCredential::where(['user_type_id' => 1])->orWhere(['user_type_id' => 2])->count('id');
        return view('main-site.home', [
            'asset_types' => AssetType::all(), 
            'asset_count' => $assetCount,
            't_sum' => $transactionSum,
            'advertiser_count' => $advertiserCount,
            'user' => $user, 
            'title' => $title
        ]);
    }


    public function redirect_login()
    {
        return view('landinglogin');
    }

    public function signup_category()
    {
        return view('main-site.signup-category');
    }

    public function faq()
    {
        $title = "Frequently Asked Questions";
        return view('main-site.faq', compact('title'));
    }


    public function contact()
    {
        $title = "Contact Us";
        return view('main-site.contact', compact('title'));
    }


    public function send_contact_mail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required',
            'email' => 'bail|required|email',
            'subject' => 'bail|required',
            'message' => 'bail|required',
        ]);

        if ( $validator->fails() ) {
            return redirect()->back()->withErrors($validator)->withInput(Input::all());
        }


        try {
            $data['to'] = $request->name;
            $data['email'] = $request->email;
            $data['subject'] = $request->subject;
            $data['message'] = $request->message;
            $data['adminCopy'] = 0;

            Mail::send('main-site.mails.contact', $data, function ($message) use($data) {
                $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                $message->sender(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                $message->to($data['email'], $data['to']);
                $message->subject("Your feedback is well received.");
            });

            $data['adminCopy'] = 1;
            Mail::send('main-site.mails.contact', $data, function ($message) use($data) {
                $message->from($data['email'], $data['to']);
                $message->sender($data['email'], $data['to']);
                $message->to(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                $message->subject($data['subject']);
            });
        } catch (\Exception $e) {
            $this->log('Critical', $e->getTraceAsString());
        }

        return redirect()->back()->with('flash_message', 'Your feedback is well received.');
    }


    public function location()
    {
        $query = implode(",", ["MALL", "CHURCHES"]);
        $APIKey = env("GOOGLE_MAP_API_KEY");
        $url = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=$query&key=$APIKey";
        $response = $this->sendRequestNow($url, 'GET');

        dd($response);
    }
}
