<?php namespace Biffy\Entities\TimeClock;

use Biffy\Entities\AbstractRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface TimeClockRepositoryInterface
 * @package Biffy\Entities\TimeClock
 */
interface TimeClockRepositoryInterface extends AbstractRepositoryInterface
{
    /**
     * @param $userId
     * @return mixed
     */
    public function getWithUser($userId);

    /**
     * @param $storeId
     * @param $userId
     * @return mixed
     */
    public function getOpenEntry($storeId, $userId);

    /**
     * @param int $storeId
     * @param int $userId
     * @param $count
     * @param $page
     * @return LengthAwarePaginator
     */
    public function getTimeClockEntries($storeId, $userId, $count, $page);

    /**
     * @param int $storeId
     * @param int $userId
     * @param string $start_time
     * @param string $end_time
     * @return mixed
     */
    public function getTimeClockEntriesBetweenTimes($storeId, $userId, $start_time, $end_time);

    public function getStoreEntries($storeId, $startTime, $endTime);

    public function clockIn($storeId, $userId);
    public function clockOut($storeId, $userId);
    public function breakStart($storeId, $userId);
    public function breakEnd($storeId, $userId);
    public function isUserClockedIn($storeId, $userId);
    public function isUserOnBreak($storeId, $userId);
}