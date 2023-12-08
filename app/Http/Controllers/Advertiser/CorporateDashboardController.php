<?php

namespace App\Http\Controllers\Advertiser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use \App\Http\Traits\TransactionTrait;
use \App\Http\Traits\UserProfileTrait;

class CorporateDashboardController extends Controller
{
    //
    use TransactionTrait, UserProfileTrait;

    public function dashboard() // Fake One
    {
        $user = \Request::get('user');

        $pending_tranx_count = $this->get_pending_payment_transaction_count();
        
        $paid_tranx_count = $this->get_paid_payment_transaction_count();

        $booked_asset_count = $this->get_booked_asset_count();

        $tokens = '';

        return view('advertiser.dashboard', compact('pending_tranx_count', 'paid_tranx_count', 'booked_asset_count', 'user', 'tokens'));
    }

    public function dashboard2() //  Real One
    {
        // $corporate_user = Auth::user(); 
        // $this->set_user_profile();
        return view('advertiser.dashboard');
    }

    public function pending_transactions()
    {
        $pending_tranx_recs = $this->retrieve_pending_transactions();

        return view('advertiser.pendingtransaction', \compact('pending_tranx_recs'));
    }

    public function logout()
    {
        Auth::logout();
        \session()->flush();
        return redirect('/advertiser/login');
    }
}
