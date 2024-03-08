<?php
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Providers\ActivityEventNotifier;

// configure the root route to this main application.
Route::get('/', 'WebsiteController@landing')->name('home');
Route::get('/landing', 'WebsiteController@landing')->name('landing');
Route::get('/login', 'WebsiteController@redirect_login');
Route::get('/signup/category', 'WebsiteController@signup_category');
Route::get('/faq', 'WebsiteController@faq');
Route::get('/contact', 'WebsiteController@contact');
Route::post('/contact', 'WebsiteController@send_contact_mail');
Route::get('/location', 'WebsiteController@location');

Route::prefix('cron')->namespace('Asset')->group(function() {
    Route::get('/release-unpaid-locked-asset', 'AssetController@handleUnpaidBookedAsset');
});

Route::namespace('Asset')->prefix('payments')->middleware(['cors'])->group(function () {
    Route::get('/globalpayvalidate', 'TransactionController@globalPayValidate');
    Route::post('/ebillsvalidate', 'TransactionController@ebillsValidate');
    Route::post('/ebillsnotify', 'TransactionController@ebillsNotify');
    Route::get('/ebillsreset', 'TransactionController@ebillsReset');
    Route::get('/getsettings', 'TransactionController@getsettings');
});

Route::get('/home', function(){
    \broadcast(new ActivityEventNotifier('asset', 'asset-payment', ['msg' => 'Payment Successful']));
});


// No gaurded environment for this route url path.
Route::get('/terms-and-conditions', 'TermsAndConditions@index');

/**
 * 
 * Create routing url path and nomencleture for accessing a guarded Advertiser
 * environment. 
 * 
 */
Route::namespace('Advertiser')->group(function(){
    
    Route::post('/signup', 'SignupController@create_advertiser');
    
    Route::get('/sendsms/{toNumbers}/{content}', 'SignupController@sendSMS');

    Route::get('/usertypes', 'SignupController@usertypes');

    // Route::get('/validate/otp/{otp}/{user_id}', 'IndividualDashboardController@validate_otp');
    Route::post('/create/campaign', 'IndividualDashboardController@create_campaign'); // Ajax post for creating new Campaign
    Route::post('/save/cart/campaign', 'IndividualDashboardController@save_campaign_cart_item'); // Ajax post for creating new item in Campaign cart
    Route::post('/search/campaign/sites', 'IndividualDashboardController@search_site_campaign'); // Ajax post for searching sites.
});

Route::namespace('Advertiser')->prefix('advertiser')->group(function () {
    
    // Route::get('/signup', 'SignupController@advertiser_signup_form');
    Route::get('/individual', 'SignupController@advertiser_signup_form');
    
    Route::get('/welcome/{name}/{token}', 'SignupController@welcome')->name('welcome');
    
    Route::get('/verify/token/{token?}', 'SignupController@verify_email');
    
    Route::get('/login', 'SignupController@login_view')->name('advertiser_login');
    
    Route::post('/login', 'SignupController@login');
    Route::get('/{slug}', 'SignupController@advertiser_signup_form');

    // Route::get('/dashboard', 'SignupController@dashboard')->name('dashboard');

    /// Guarded Corporate Environment.
    Route::middleware(['corporate'])->prefix('corporate')->group(function () {
        Route::get('/dashboard', 'CorporateDashboardController@dashboard')->name('CorporateDashboard');
        Route::get('/pending/transaction', 'CorporateDashboardController@pending_transactions')->name('corporatePendingTransaction');  
    }); 

    

    /// Guarded Individual Environment.
    Route::middleware(['individual'])->prefix('individual')->group(function () {
        Route::get('/dashboard', 'IndividualDashboardController@dashboard')->name('IndividualDashboard');
        Route::get('/logout', 'IndividualDashboardController@logout');
        Route::get('/transactions/pending/{type?}', 'IndividualDashboardController@pending_transactions')->name('individaulPendingTransaction'); 
        Route::get('/transactions/pending/{booking_id}/{booking_type?}/{booking_type_id?}/delete', 'IndividualDashboardController@delete_pending_transactions'); 
        Route::get('/payment-history', 'IndividualDashboardController@payment_history')->name('individaulPaymentHistory'); 
        Route::get('/transactions/historical', 'IndividualDashboardController@transaction_history')->name('individaulTransactionHistory');
        Route::get('/pending/transaction/payments/detail/{type}/{booking_id}', 'IndividualDashboardController@pending_transaction_payments_detail');
        Route::get('/paid/transaction/payments/detail/{booking_id}', 'IndividualDashboardController@paid_transaction_payments_detail');
        Route::get('/pending/transaction/payments/regenerate-reference/{booking_id}/{tranx_id}', 'IndividualDashboardController@regenerateTransactionReference'); 
        Route::get('/generate/nibbs-transaction-code', 'IndividualDashboardController@generate_nibbs_transaction_code');

        Route::get('/myassets', 'IndividualDashboardController@myassets'); 
        Route::get('/profile/edit/', 'IndividualDashboardController@edit_profile_view'); 
        Route::post('/profile/edit/', 'IndividualDashboardController@edit_profile');
        Route::get('/profile/changepassword/', 'IndividualDashboardController@change_password_view'); 
        Route::post('/profile/changepassword/', 'IndividualDashboardController@change_password');
        Route::get('/material', 'IndividualDashboardController@material_view'); 
        Route::post('/material', 'IndividualDashboardController@material');
        Route::get('/search/advanced', 'IndividualDashboardController@advanced_search_view');
        Route::post('/search/advanced/results', 'IndividualDashboardController@advanced_search_result_view');
        // Route::get('/generate/otp', 'IndividualDashboardController@generate_otp');
        Route::get('/create/campaign/{campaign_id?}', 'IndividualDashboardController@create_campaign_view');
        Route::get('/view/campaign', 'IndividualDashboardController@campaign_view');
        Route::get('/replace/campaign/{old_asset_id?}/{new_asset_id?}', 'IndividualDashboardController@replace_campaign_view');
        Route::get('/remove/campaign/{campaign_id?}', 'IndividualDashboardController@remove_campaign_view');

        Route::get('/fast-track', 'IndividualDashboardController@fast_track');
        Route::get('/fast-track/exit', 'IndividualDashboardController@exit_fast_track');

        Route::get('/corporate/profile/edit', 'IndividualDashboardController@edit_corporate_profile_view');
        Route::post('/corporate/profile/edit', 'IndividualDashboardController@edit_corporate_profile');
        Route::get('/corporate/dashboard', 'IndividualDashboardController@corporate_dashboard');
        Route::get('/corporate/bookings', 'IndividualDashboardController@corporate_bookings');
        Route::get('/corporate/dealslip/{booking_id?}/{user_id?}', 'IndividualDashboardController@corporate_dealslip');
        Route::get('/corporate/staffs', 'IndividualDashboardController@corporate_staffs');
        Route::get('/corporate/staff/{staff_id?}', 'IndividualDashboardController@corporate_staff_details');
        
    });
});



/**
 * 
 * Create routing url path and nomencleture for accessing a guarded Operator
 * environment. 
 * 
 */

Route::namespace('Operator')->prefix('operator')->group(function () {
    
    Route::get('/signup', 'SignupController@operator_signup_form');
    Route::post('/search', 'SignupController@search_operator');
    Route::get('/verification', 'SignupController@verify_operator');
    Route::post('/signup', 'SignupController@create_operator');
    Route::get('/verify/token/{token?}', 'SignupController@verify_email');
    Route::get('/login', 'SignupController@login_view')->name('operator_login');
    Route::post('/login', 'SignupController@login');
    Route::get('/welcome/{name}/{token}', 'SignupController@welcome')->name('operator_welcome');

    Route::middleware(['operator.dashboard'])->group(function () {
        Route::get('/dashboard', 'DashboardController@dashboard')->name('operator_dashboard');
        Route::get('/assetupload', 'DashboardController@assetupload_form');
        Route::post('/asset/upload', 'DashboardController@create_assetupload'); 
        Route::get('/payment-history', 'DashboardController@payment_history');
        Route::get('/vacantasset', 'DashboardController@vacant_asset');
        Route::get('/totalasset', 'DashboardController@total_asset');
        Route::get('/edit-asset-video/{id}', 'DashboardController@view_edit_asset_video');
        Route::post('/edit-asset-video/upload/{id}', 'DashboardController@edit_asset_video');
        Route::get('/bookedasset', 'DashboardController@booked_asset');
        Route::get('/materials', 'DashboardController@materials');
        Route::get('/material-download/{id}', 'DashboardController@material_download');
        Route::get('/subscription', 'DashboardController@subscription_view');
        Route::post('/subscription', 'DashboardController@subscription'); 
        Route::get('/bank-account-setup', 'DashboardController@bank_account_setup'); 
        Route::get('/transaction/regenerate-reference/{booking_id}/{id}', 'DashboardController@regenerateTransactionReference'); 
        Route::post('/update-bank-account-setup', 'DashboardController@update_bank_account_setup'); 
        Route::get('/staffs', 'DashboardController@staffs_view');
        Route::get('/edit-staff/{id}', 'DashboardController@edit_staffs_view');
        Route::get('/create-staff', 'DashboardController@create_staff_view');
        Route::post('/create-staff', 'DashboardController@create_staff');
        Route::post('/edit_staff', 'DashboardController@edit_staff');
        Route::get('/logout', 'DashboardController@logout');
    });
});


/**
 * 
 * Create routing url path and nomencleture for accessing a non guarded Asset
 * environment. 
 * 
 */
Route::namespace('Asset')->middleware('asset')->prefix('asset')->group(function() {
    Route::post('/available/{name?}', 'AssetController@available_asset');
    Route::get('/available/{name?}', 'AssetController@available_asset')->name('available_asset');
    Route::get('/{asset_id}/detail', 'AssetController@asset_detail')->name('asset_detail');
    Route::get('/details', 'AssetController@details');
    Route::get('/states', 'AssetController@get_states');
    Route::get('/states/{state_id}/lga', 'AssetController@get_lga');
    Route::get('/states/{state_id}/lcda', 'AssetController@get_lcda');
    Route::get('/search-asset-by-area', 'AssetController@asset_area_auto_complete_search');
    // Route::post('/states', 'AssetController@save_states');
    // Route::post('/states/lga', 'AssetController@save_lga');

    Route::middleware(['individual'])->group(function() {
        Route::get('transactions/{tranx_id?}', 'TransactionController@transactions')->name('transactions');
        Route::post('book', 'AssetController@book_single_asset')->name('book_asset');   
        Route::post('/campaign/create/transaction', 'AssetController@book_asset_campaign');
    });
});


/**
 * 
 * 
 */
Route::namespace('Admin')->prefix('admin')->group(function(){
    Route::get('/', function() {return redirect('admin/login');});
    Route::get('/login', 'AdminUserCredentialController@login_form')->name('admin_login');
    Route::post('/login', 'AdminUserCredentialController@login');

    Route::get('/signup', 'AdminUserController@signup_form')->name('admin_signup');
    Route::post('/signup', 'AdminUserController@signup');

    Route::middleware('admin')->group(function(){
        Route::get('/dashboard', 'AdminUserDashboardController@dashboard')->name('admin_dashboard');
        Route::get('/disbursed/pending', 'AdminUserDashboardController@payment_disbursement_view');
        Route::get('/disbursed/payment/requery/{txid}', 'AdminUserDashboardController@payment_disbursement_requery');
        Route::post('/disbursed/pending', 'AdminUserDashboardController@payment_disbursement');
        Route::get('/disbursed/payment', 'AdminUserDashboardController@disbursed_payment_view');
        Route::get('/reverse/disbursed/payment/{txid?}', 'AdminUserDashboardController@reverse_disbursed_payment');
        Route::get('/pending/payment-schedule', 'AdminUserDashboardController@pending_payment_schedule_view');
        Route::get('/platform/bookings', 'AdminUserDashboardController@asset_booking_view');
        Route::get('/platform/assets/{operatorId?}', 'AdminUserDashboardController@assets_view');
        Route::get('/platform/users', 'AdminUserDashboardController@users_view');
        Route::get('/platform/batch-asset-upload', 'AdminUserDashboardController@batch_asset_upload_view');
        Route::post('/platform/batch-asset-upload', 'AdminUserDashboardController@batch_asset_upload');
        Route::get('/platform/asset-upload-media/{media}/{id}', 'AdminUserDashboardController@batch_asset_media_upload_view');
        Route::post('/platform/asset-upload-media/{media}/{id}', 'AdminUserDashboardController@batch_asset_media_upload');
        Route::get('/settings', 'AdminUserDashboardController@settings_view');
        Route::post('/settings', 'AdminUserDashboardController@settings');
        Route::get('/settings/run-utility/{utility}', 'AdminUserDashboardController@run_utility');
        Route::get('/logout', 'AdminUserDashboardController@logout');
    });
    
});