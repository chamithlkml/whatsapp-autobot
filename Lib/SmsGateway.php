<?php
require_once (__DIR__ . '/vendor/autoload.php');

class SmsGateway
{
    public function sendMessage($message, $recipient)
    {
        $api_instance = new NotifyLk\Api\SmsApi();
        $user_id = NOTIFY_LK_USER_ID; // string | API User ID - Can be found in your settings page.
        $api_key = NOTIFY_LK_API_KEY; // string | API Key - Can be found in your settings page.
        $to = $this->getValidContact($recipient);
        \Logger::info('to number: ' . $to);
        $sender_id = NOTIFY_LK_SENDER_ID; // string | This is the from name recipient will see as the sender of the SMS. Use \\\"NotifyDemo\\\" if you have not ordered your own sender ID yet.
        $contact_fname = ""; // string | Contact First Name - This will be used while saving the phone number in your Notify contacts (optional).
        $contact_lname = ""; // string | Contact Last Name - This will be used while saving the phone number in your Notify contacts (optional).
        $contact_email = ""; // string | Contact Email Address - This will be used while saving the phone number in your Notify contacts (optional).
        $contact_address = ""; // string | Contact Physical Address - This will be used while saving the phone number in your Notify contacts (optional).
        $contact_group = 0; // int | A group ID to associate the saving contact with (optional).
        $type = null; // string | Message type. Provide as unicode to support unicode (optional).

        try {
            $api_instance->sendSMS($user_id, $api_key, $message, $to, $sender_id, $contact_fname, $contact_lname, $contact_email, $contact_address, $contact_group, $type);
            \Logger::info('SMS Sent to ' . $to);
        } catch (\Exception $e) {
            \Logger::error("error occurred while sending SMS: " . $e->getMessage());
            \Logger::error($e->getTraceAsString());
        }
    }

    private function getValidContact($contact){
        $valid_contact = str_replace(' ', '', $contact);

        if(substr($valid_contact, 0, 1) == '+')
        {
            $valid_contact = substr($valid_contact, 1);
        }

        return $valid_contact;
    }
}