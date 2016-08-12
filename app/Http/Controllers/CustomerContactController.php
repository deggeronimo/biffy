<?php namespace Biffy\Http\Controllers;

use Biffy\Entities\Customer\Customer;
use Biffy\Entities\CustomerContact\CustomerContact;

/**
 * Class TwilioController
 * @package Biffy\Http\Controllers
 */
class CustomerContactController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    public function saveLog($params)
    {
        $contracts = new CustomerContact;
        $contracts->type = $params['type'];
        $contracts->direction = $params['direction'];
        $contracts->phone = $params['phone'];
        $customer_id = \DB::table('customers')->where('phone', '=', "{$contracts->phone}")->select('id')->get();
        if (empty($customer_id))
            $contracts->customer_id = null;
        else {
            $customer = Customer::find($customer_id[0]->id);
            $contracts->customer()->associate($customer);
        }
        $contracts->status = $params['status'];
        $contracts->content = $params['content'];
        if ($params['duration'] == null)
            $contracts->duration = 0;
        else
            $contracts->duration = $params['duration'];
        $date = date_parse_from_format("D, d M Y H:i:s O", $params['date']);
        $date = date("Y-m-d H:i:s", mktime($date['hour'], $date['minute'], $date['second'], $date['month'], $date['day'], $date['year']));
        // $date = date("D, d M Y H:i:s O", $params['date']);
        $contracts->date = $date;
        $contracts->callid = $params['callid'];
        return $contracts->save().' '.print_r($params, true);
    }

    public function postChecksms() {
        $unreads = \DB::table('customer_contacts')->where('type', 'sms')->where('sms_read', false)->where('direction', 'inbound')->get();
        if (empty($unreads))
            return $this->data(array('data' => 'none'))->respond();

        $data = array();
        foreach ($unreads as $unread) {
            $each = array();
            if ($unread->customer_id == null) {
                $each['customer'] = '';
            } else {
                $customer = Customer::find($unread->customer_id);
                $each['customer'] = $customer->full_name;
            }
            $each['phone'] = $unread->phone;
            $each['content'] = $unread->content;
            $customer_contact = CustomerContact::find($unread->id);
            $customer_contact->sms_read = true;
            $customer_contact->save();
            $data[] = $each;
        }
        return $this->data(array('data' => $data))->respond();
    }

    public function getAll() {
        $contacts = CustomerContact::all();
        $data = array();
        foreach ($contacts as $contact) {
            $arr = array();
            $arr[] = $contact->id;
            $arr[] = $contact->phone;
            $arr[] = $contact->customer_id;
            $arr[] = $contact->type;
            $arr[] = $contact->direction;
            $arr[] = $contact->status;
            $arr[] = $contact->content;
            $arr[] = $contact->duration;
            $arr[] = $contact->date;
            $arr[] = $contact->sms_read;
            $data[] = $arr;
        }
        print_r($data);
    }
}