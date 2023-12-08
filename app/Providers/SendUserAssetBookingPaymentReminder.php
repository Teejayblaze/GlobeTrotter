<?php

namespace App\Providers;

use App\Providers\UserAssetBookingPaymentReminder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\UserNotification;
use App\Http\Traits\LoggerTrait;
use Illuminate\Database\QueryException;
use App\Mail\SendUserAssetBookingPaymentReminderMail;
use App\Http\Traits\SMSTrait;
use Illuminate\Support\Facades\Mail;

class SendUserAssetBookingPaymentReminder
{
    use LoggerTrait, SMSTrait;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function format_web_msg($grace_hrs, $booked_asset_name)
    {
        return '<div class="links">'.
                    '<p>Kindly be informed that you have less than '. $grace_hrs .'hrs to make payment for the <strong>'. $booked_asset_name .'</strong> reserved asset.</p>'.
                    '<p>Please also note that if payment is not made this asset will be made available to other advertisers who are waiting for it.</p>'.
                    '<p></p>'.
                    '<p><strong>Best Regards,</strong></p>'.
                    '<strong>'. config('app.name') .' Team.</strong>'.
                '</div>';
    }

    public function format_mobile_msg($grace_hrs, $booked_asset_name)
    {
        return 'Kindly be informed that you have less than '. $grace_hrs .'hrs to make payment for the '. $booked_asset_name .
            ' reserved asset.\r\n Please also note that if payment is not made this asset will be made available to '.
            'other advertisers who are waiting for it.';
    }

    /**
     * Handle the event.
     *
     * @param  UserAssetBookingPaymentReminder  $event
     * @return void
     */
    public function handle(UserAssetBookingPaymentReminder $event)
    {

        Mail::to($event->user->email)->send(new SendUserAssetBookingPaymentReminderMail($event));
        //
        // $userNotify = new UserNotification();
        // $userNotify->user_id = $event->assetBooking->booked_by_user_id;
        // $userNotify->user_type_id = $event->assetBooking->user_type_id;
        // $userNotify->message = $this->format_web_msg($event->grace_hrs, $event->assetBooking->asset->name);
        // $userNotify->read_status = 0;

        // try {

        //     if ( $userNotify->save() ) {
        
        //         $this->log('success', 'In-App User Notifcation Record For Asset Booking Payment Reminder Created Successfully.');

        //         Mail::to($event->user->email)->send(new SendUserAssetBookingPaymentReminderMail($event));

        //         $this->log('success', 'Asset Booking Payment Reminder Mail Sent Successfully.');

        //         $sentSMS = $this->sendSMSNow($event->user->phone, $this->format_mobile_msg($event->grace_hrs, $event->assetBooking->asset->name));
                
        //         $sentSMS = json_decode($sentSMS->getContent());

        //         if ( $sentSMS->status ) $this->log('success', 'Asset Booking Payment Reminder SMS Sent Successfully.');
        //     }

        // } catch (QueryException $qe) {
        //     $this->log('Critical', $qe->getMessage());
        // }
    }
}
