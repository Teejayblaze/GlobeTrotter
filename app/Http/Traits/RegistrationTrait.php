<?php

namespace App\Http\Traits;
use App\UserCredential;
use App\Providers\UserRegistered;
// use App\Http\Traits\LoggerTrait;

trait RegistrationTrait
{
    // use LoggerTrait;

    /**
     * Handle the saving of the credential in the DB irrespective of the role
     * (Individual | Corporate)
     * 
     * @parameter string $email, string $password, int $inserted_id, string $user_type, int $operator
     */
    private function save_user_credentials(string $email, string $password, int $inserted_id, string $user_type, int $operator = 0, int $admin = 0)
    {
        $credentials = new UserCredential();

        $credentials->email = $email;
        $credentials->password = bcrypt($password);
        $credentials->user_id = $inserted_id;
        $credentials->user_type_id = intval($user_type);
        $credentials->operator = $operator;
        $credentials->admin = $admin;

        try {
            return $credentials->save();
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->log('Critical', $ex->getMessage()); // Log the exception here.
        }
    }

    /**
     * Notify the application about the newly created event and send the 
     * newly created user a mail.
     * 
     * First create the credential of the user and associate the user to 
     * a role (Corporate | Individual).
     */
    private function fire_create_user_event($created_user, string $mail_template = 'advertiser.mails.signup', string $group = 'advertiser')
    {
        if ($created_user) {
            
            try {
                // event(new UserRegistered($created_user));
                broadcast(new UserRegistered($created_user, $mail_template, $group));
            } catch (\Exception $ex) {
                $this->log('Info', $ex->getMessage());
            }

            $name = null;
            if ( $created_user->name ) $name = $created_user->name;
            else if ( $created_user->lastname ) $name = $created_user->lastname;
            else $name = $created_user->corporate_name;

            return response()->json([
                'status' => true, 
                'errors' => null, 
                'success' => 'Account created successfully.',
                'link' => route('welcome', ['name' => $name, 'token' => $created_user->token])
            ]); 
        }
    }
}
