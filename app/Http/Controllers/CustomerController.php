<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\Customer\CustomerService;
use Biffy\Entities\Customer\Customer;

/**
 * Class CustomerController
 * @package Biffy\Http\Controllers
 */
class CustomerController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    /**
     * @var CustomerService $service
     */
    protected $service;

    protected $morphTo = [
        'list' => [ 'results' => [ '[]' => [ 'id', 'given_name', 'family_name', 'phone', 'store' => [ 'name' ] ] ] ]
    ];

    /**
     * @param CustomerService $service
     */
    function __construct(CustomerService $service)
    {
        $this->service = $service;
    }

    protected function beforeStore(array $input)
    {
        $input['full_name'] = $input['given_name'] . ' ' . $input['family_name'];

        return $input;
    }

    protected function beforeUpdate($id, array $input)
    {
        if (isset($input['given_name']) && isset($input['family_name']))
        {
            $input['full_name'] = $input['given_name'] . ' ' . $input['family_name'];
        }

        return $input;
    }

    public function export()
    {
        \Excel::create('customers', function($excel)
        {
            $excel->setTitle('List of customers');
            $excel->setCreator(config('info.export.creator'))->setCompany(config('info.export.company'));

            $excel->sheet('Customers', function($sheet)
            {
                $filter = $this->input('filter');
                $sorting = $this->input('sorting');

                //Export result does not limit records but maintains filter and sorting values
                $customers = $this->service->getCustomerList($filter, $sorting);

                $sheet->loadView('exports.CustomersList', compact('customers'));
            });
        })->download(config('info.export.extension'));
    }

    public function postList() {
        $key = \Input::get('search_key');
        $customers = \DB::table('customers')->where('full_name', 'like', "%{$key}%")->select('phone', 'full_name')->take(10)->get();
        return $this->data(array('list' => $customers))->respond();
    }

    public function postByphone() {
        $phone = \Input::get('phone');
        // $phone = substr($phone, -10);
        $customer = \DB::table('customers')->where('phone', '=', "{$phone}")->select('full_name')->get();
        return $this->data(array('customer' => $customer))->respond();
    }
}