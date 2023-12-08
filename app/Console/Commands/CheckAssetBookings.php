<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
// use Carbon\Carbon;
use App\Corporate;
use App\Individual;
use App\AssetBooking;
use App\AssetGracePeriod;
use App\Providers\UserAssetBookingPaymentReminder;
use App\Http\Traits\LoggerTrait;
use DateTime;
use DateTimeZone;

class CheckAssetBookings extends Command
{
    use LoggerTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:assets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Module runs every 6 hours, check the database to remind an advertiser who has booked an asset and is yet to make payment of his/her remaining hours of grace (grace period is usually 48 hours.)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $GRACEPRD = 48;
        $user = null;
        $asset_booking_records = AssetBooking::where('locked', '=', 1)->get();

        $this->releaseUnpaidBookedAsset($asset_booking_records, $GRACEPRD);
    }


    private function sendGracePeriod48HrsReminder($asset_booking_records, $GRACEPRD) {
        foreach ($asset_booking_records as $key => $asset_booking_record) {
            
            $asset_start_date = Carbon::parse($asset_booking_record->start_date);

            if ($date->lt($asset_start_date)) { 
                /**
                 *  Today being less than start date, let's start reminding advertisers of pending payment on their Deal Slip
                 *  'hurray today '.$date->format('Y-m-d').' is less than start date '.$asset_start_date->format('Y-m-d')
                 */ 
                $asset_grace_period_records = AssetGracePeriod::where([
                    ['asset_booking_id', '=', $asset_booking_record->id],
                    ['completed', '=', 0],
                ])->get();
                
                foreach ($asset_grace_period_records as $key => $asset_grace_period_record) {

                    $asset_grace_period_ends = Carbon::parse($asset_grace_period_record->grace_period_ends);
                    $grace_reminder_hrs_left = $date->diffInHours($asset_grace_period_ends);

                    if ($grace_reminder_hrs_left <= $grace_hrs) {
                        /**
                         * STEP 1:                         * 
                         *  Kindly notify the advertiser continuously of the remaining hours of grace to make payment
                         *  otherwise he/she will loose the asset he has booked.
                         * 
                         * STEP 2:
                         *  Retrieve the details of the Advertiser that booked this Asset and prepare to send him/her
                         *  the reminder notification.
                         * 
                         * STEP 3:
                         *  Retrieve the Asset Details as well to remember the asset name that Advertiser booked.
                         * 
                         * STEP 4:
                         *  Broadcast the notifications to Individual/Corporate Advertisers.
                         */
                        if ( $asset_booking_record->user_type_id === 1 ) {
                            $user = Corporate::where('id', '=', $asset_booking_record->booked_by_user_id)->first();
                        } else {
                            $user = Individual::where('id', '=', $asset_booking_record->booked_by_user_id)->first();
                        }  
                        
                        // Broadcast the remainder.
                        broadcast(new UserAssetBookingPaymentReminder($asset_booking_record, $grace_reminder_hrs_left, $user));
                    
                    } else {
                        /**
                         *  Suddenly, our 48hrs grace period for payment has elapsed.
                         *  So, lets take action by unlocking/unreserve the asset.
                         */
                        $asset_booking_record->locked = 0;
                        $asset_booking_record->save();
                    }
                }
            }
        } 
    }

    private function releaseUnpaidBookedAsset($asset_booking_records, $GRACEPRD) {
        $asset_booking_records = AssetBooking::where('locked', '=', 1)->get();
        $timeZone = new DateTimeZone("Africa/Lagos");
        foreach ($asset_booking_records as $key => $asset_booking_record) {
            $hasPaidTransaction = $asset_booking_record->transaction()->where([
                ['asset_booking_ref', '=', $asset_booking_record->trnx_id],
                ['bank_ref_number', '<>', null],
                ['paid', '=', 1],
                ['subscription', '=', 0]
            ])->count();
            if ($hasPaidTransaction <= 0) {
                $startDate = new DateTime($asset_booking_record->created_at, $timeZone);
                $endDate = new DateTime('now', $timeZone);
                $start = strtotime($startDate->format('Y-m-d H:i:s'));
                $end = strtotime($endDate->format('Y-m-d H:i:s'));
                $diff = intval(($end - $start)/(60*60)); // 60 * 60 = 1hr; hence why we are dividing by hr
                if ($diff > $GRACEPRD) {
                    $asset_booking_record->update(['locked' => 0]);
                    $asset_name = $asset_booking_record->asset->name;
                    $description = "Asset [$asset_name] with the booking number [$asset_booking_record->trnx_id] has been released from the SML(System Monitor Lock), having been booked for more than 48hrs without payment.";
                    $this->log("info", $description);
                }
            }
        }
    }
}
