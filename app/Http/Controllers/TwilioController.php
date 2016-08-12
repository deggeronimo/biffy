<?php namespace Biffy\Http\Controllers;

/**
 * Class TwilioController
 * @package Biffy\Http\Controllers
 */
use Biffy\Http\Controllers\CustomerContactController;

class TwilioController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    protected $customerContact;

    function __construct()
    {
        $this->accountId = "ACeaa69400a673631ab1780d0a5f333730";
        $this->token = "eb1a6920ecd488e0fb15ff920a078253";
        $this->appId = "APafd2feb6d86a113d100a147fd394fc26";
        $this->fromNumber = "14074568398";
        $this->customerContact = new CustomerContactController;
        require_once(base_path().'/vendor/twilio/sdk/Services/Twilio.php');
    }

    public function postToken()
    {
        $this->accountId = "ACeaa69400a673631ab1780d0a5f333730";
        $this->token = "eb1a6920ecd488e0fb15ff920a078253";
        require base_path().'/vendor/twilio/sdk/Services/Twilio/Capability.php';
        $service_capa = "Services_Twilio_Capability";
        $capability = new $service_capa($this->accountId, $this->token);
        $capability->allowClientOutgoing($this->appId);
        $capability->allowClientIncoming('alice');
        $token = $capability->generateToken();
        return $this->data(array('token' => $token))->respond();
    }

    public function postMessage()
    {
        $number = \Input::get('phoneNumber');
        $message = \Input::get('message');
        $twilio = new \Aloha\Twilio\Twilio($this->accountId, $this->token, $this->fromNumber);
        $twilio->message($number, $message);
        return $this->data(array('success' => 'true'))->respond();
    }

    public function postLoginfo() {
        $callid = \Input::get('callid');
        $isIncoming = \Input::get('isIncoming');
        $service_twilio = "Services_Twilio";
        $client = new $service_twilio($this->accountId, $this->token);
        $call = $client->account->calls->get($callid);

        if ($isIncoming) {
            $parentid = $call->parent_call_sid;
            $parentcall = $client->account->calls->get($parentid);
            $params = array();
            $params['type'] = 'voice';
            $params['direction'] = $parentcall->direction;
            $params['phone'] = $parentcall->from;
            $params['status'] = $call->status;
            $params['content'] = '';
            foreach ($client->account->recordings->getIterator(0, 50, array('CallSid' => $parentcall->sid)) as $recording) {
                $params['content'] = 'https://api.twilio.com'.$recording->uri;
                break;
            }
            $params['duration'] = $call->duration;
            $params['date'] = $parentcall->start_time;
            $params['callid'] = $parentcall->sid;
            $isSuccess = $this->customerContact->saveLog($params);
        } else {
            foreach ($client->account->calls->getIterator(0, 50, array('ParentCallSid' => $callid)) as $call) {
                $params = array();
                $params['type'] = 'voice';
                $params['direction'] = $call->direction;
                $params['phone'] = $call->to;
                $params['status'] = $call->status;
                $params['content'] = '';
                foreach ($client->account->recordings->getIterator(0, 50, array('CallSid' => $call->sid)) as $recording) {
                    $params['content'] = 'https://api.twilio.com'.$recording->uri;
                    break;
                }
                $params['duration'] = $call->duration;
                $params['date'] = $call->start_time;
                $params['callid'] = $call->sid;
                $isSuccess = $this->customerContact->saveLog($params);
                return $this->data(array('success' => $isSuccess))->respond();
            }
        }
        return $this->data(array('success' => $isSuccess))->respond();
    }

    public function postSmsinfo() {
        $service_twilio = "Services_Twilio";
        $client = new $service_twilio($this->accountId, $this->token);
        foreach ($client->account->messages->getIterator(0, 50, array()) as $message) {
            $params = array();
            $params['type'] = 'sms';
            $params['direction'] = $message->direction;
            $params['phone'] = $message->from;
            $params['status'] = $message->status;
            $params['content'] = $message->body;
            $params['duration'] = 0;
            $params['date'] = $message->date_sent;
            $params['callid'] = $message->sid;
            $isSuccess = $this->customerContact->saveLog($params);
            return $this->data(array('success' => $isSuccess))->respond();
        }
    }

    public function getSmsrequest() {
        $service_twilio = "Services_Twilio";
        $client = new $service_twilio($this->accountId, $this->token);
        
        $mm_id = $_REQUEST['MessageSid'];
        $message = $client->account->messages->get($mm_id);
        if ($message != null) {
            $params = array();
            $params['type'] = 'sms';
            $params['direction'] = $message->direction;
            $params['phone'] = $message->from;
            $params['status'] = $message->status;
            $params['content'] = $message->body;
            $params['duration'] = 0;
            $params['date'] = $message->date_sent;
            $params['callid'] = $message->sid;
            $isSuccess = $this->customerContact->saveLog($params);
        }

        $service_twilio = "Services_Twilio_Twiml";
        $response = new $service_twilio();
        print $response;
    }
}