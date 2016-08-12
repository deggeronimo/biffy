<?php namespace Biffy\Http\Controllers;

use Biffy\Entities\TimeClock\TimeClock;
use Biffy\Services\Entities\TimeClock\TimeClockService;
use Carbon\Carbon;

/**
 * Class TimeClockController
 * @package Biffy\Http\Controllers
 */
class TimeClockController extends ApiController
{
    /**
     * @var TimeClockService
     */
    protected $service;

    /**
     * @param TimeClockService $service
     */
    function __construct(TimeClockService $service)
    {
        $this->service = $service;
    }

    public function getEntries()
    {
        $count = $this->input('count', \StoreConfig::get('results-per-page', 10));
        $page = $this->input('page', 1);

        $paginator = $this->service->getTimeClockEntries(\Auth::user()->storeId(), \Auth::user()->userId(), $count, $page);
        $entries = $this->service->handlePaginatedData($paginator);
        $clockStatus = $this->service->getClockStatus(\Auth::user()->storeId(), \Auth::user()->userId());

        return $this->data(['entries' => $entries, 'clockedIn' => $clockStatus['clockedIn'], 'onBreak' => $clockStatus['onBreak']])->paginator($paginator)->respond();
    }

    public function getDetails($userId)
    {
        $count = $this->input('count', \StoreConfig::get('results-per-page', 10));
        $page = $this->input('page', 1);
        $from = $this->input('from');
        $to = $this->input('to');

        $paginator = $this->service->getTimeClockEntries(\Auth::user()->storeId(), $userId, $count, $page, $from, $to);
        $entries = $this->service->handlePaginatedData($paginator);

        return $this->data(['entries' => $entries])->paginator($paginator)->respond();
    }

    public function postClockIn()
    {
        $errCheck = $this->service->clockIn(\Auth::user()->storeId(), \Auth::user()->userId());
        return $this->errorCheck($errCheck);
    }

    public function postClockOut()
    {
        $errCheck = $this->service->clockOut(\Auth::user()->storeId(), \Auth::user()->userId());
        return $this->errorCheck($errCheck);
    }

    public function postBreakStart()
    {
        $errCheck = $this->service->breakStart(\Auth::user()->storeId(), \Auth::user()->userId());
        return $this->errorCheck($errCheck);
    }

    public function postBreakEnd()
    {
        $errCheck = $this->service->breakEnd(\Auth::user()->storeId(), \Auth::user()->userId());
        return $this->errorCheck($errCheck);
    }

    public function getPayPeriod()
    {
        return $this->data(['day' => \StoreConfig::get('pay-period-start', Carbon::THURSDAY)])->respond();
    }

    public function putEdit($id)
    {
        $entry['time_in'] = $this->input('time_in');
        $entry['time_out'] = $this->input('time_out');

        // todo validate times
        $this->service->update($id, $entry);
    }

    public function postAdd()
    {
        // todo validate times
        $this->service->create([
                'store_id' => \Auth::user()->storeId(),
                'user_id' => $this->input('user_id'),
                'time_in' => $this->input('time_in'),
                'time_out' => $this->input('time_out'),
                'clock_type' => $this->input('clock_type') === 'work' ? TimeClock::CLOCKTYPE_ONCLOCK : TimeClock::CLOCKTYPE_ONBREAK
            ]);

        return $this->statusCreated()->respond();
    }

    public function deleteEntry($id)
    {
        $this->service->destroy($id);
        return $this->statusDeleted()->respond();
    }

    protected function errorCheck($check)
    {
        if (array_key_exists('error', $check)) {
            return $this->data($check)->statusConflict()->respond();
        }

        return $this->data($check)->respond();
    }
}