<?php namespace Biffy\Services\Entities\TimeClock;

use Biffy\Commands\TimeClock\ClockInCommand;
use Biffy\Commands\TimeClock\ClockOutCommand;
use Biffy\Commands\TimeClock\OffBreakCommand;
use Biffy\Commands\TimeClock\OnBreakCommand;
use Biffy\Commands\TimeClock\VerifyClockedInCommand;
use Biffy\Commands\TimeClock\VerifyNotClockedInCommand;
use Biffy\Commands\TimeClock\VerifyNotOnBreakCommand;
use Biffy\Commands\TimeClock\VerifyOnBreakCommand;
use Biffy\Entities\TimeClock\TimeClockRepositoryInterface;
use Biffy\Services\Entities\Service;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class TimeClockService
 * @package Biffy\Services\Entities\TimeClock
 */
class TimeClockService extends Service
{
    /**
     * @param TimeClockRepositoryInterface $repo
     */
    public function __construct(TimeClockRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getClockStatus($storeId, $userId)
    {
        $result = $this->repo->getOpenEntry($storeId, $userId);

        if ($result->count()) {
            $openEntry = $result->first();
            $clockType = $openEntry['clock_type'];

            return ['clockedIn' => $clockType == 1, 'onBreak' => $clockType == 2];
        }

        return ['clockedIn' => false, 'onBreak' => false];
    }

    /**
     * @param int $storeId
     * @param int $userId
     * @return array
     */
    public function clockIn($storeId, $userId)
    {
        $command = [
            'store_id' => $storeId,
            'user_id' => $userId
        ];

        $this->register([
            new VerifyNotClockedInCommand($command),
            new VerifyNotOnBreakCommand($command),
            new ClockInCommand($command)
        ]);

        return $this->execute();
    }

    /**
     * @param int $storeId
     * @param int $userId
     * @return array
     */
    public function clockOut($storeId, $userId)
    {
        $command = [
            'store_id' => $storeId,
            'user_id' => $userId
        ];

        $this->register([
            new VerifyClockedInCommand($command),
            new ClockOutCommand($command)
        ]);

        return $this->execute();
    }

    /**
     * @param int $storeId
     * @param int $userId
     * @return array
     */
    public function breakStart($storeId, $userId)
    {
        $command = [
            'store_id' => $storeId,
            'user_id' => $userId
        ];

        $this->register([
            new VerifyClockedInCommand($command),
            new VerifyNotOnBreakCommand($command),
            new ClockOutCommand($command),
            new OnBreakCommand($command)
        ]);

        return $this->execute();
    }

    /**
     * @param int $storeId
     * @param int $userId
     * @return array
     */
    public function breakEnd($storeId, $userId)
    {
        $command = [
            'store_id' => $storeId,
            'user_id' => $userId
        ];

        $this->register([
            new VerifyNotClockedInCommand($command),
            new VerifyOnBreakCommand($command),
            new OffBreakCommand($command),
            new ClockInCommand($command)
        ]);

        return $this->execute();
    }

    /**
     * @param int $storeId
     * @param int $userId
     * @param $count
     * @param $page
     * @param string|null $from
     * @param string|null $to
     * @return LengthAwarePaginator
     */
    public function getTimeClockEntries($storeId, $userId, $count, $page, $from = null, $to = null)
    {
        return $this->repo->getTimeClockEntries($storeId, $userId, $count, $page, $from, $to);
    }

    /**
     * @param LengthAwarePaginator $paginator
     * @return array
     */
    public function handlePaginatedData($paginator)
    {
        return array_map(function ($entry) {
                $entry = $entry->toArray();
                $entry['hours'] = \Time::diffInHours($entry['time_in'], $entry['time_out']);
                $entry['time_in'] = \Time::strToMs($entry['time_in']);
                $entry['time_out'] = \Time::strToMs($entry['time_out']) ?: '';
                return $entry;
            }, $paginator->items());
    }

    /**
     * @param int $storeId
     * @param int $userId
     * @param string $startTime
     * @param string $endTime
     * @return array
     */
    public function getTimeClockEntriesBetweenTimes($storeId, $userId, $startTime, $endTime)
    {
        return $this->repo->getTimeClockEntriesBetweenTimes($storeId, $userId, $startTime, $endTime)->toArray();
    }

    public function getStoreEntries($storeId, $startTime, $endTime)
    {
        return $this->repo->getStoreEntries($storeId, $startTime, $endTime);
    }
}
