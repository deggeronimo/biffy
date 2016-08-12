<?php namespace Biffy\Entities\TimeClock;

use Biffy\Entities\EloquentAbstractRepository;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class EloquentTimeClockRepository
 * @package Biffy\Entities\TimeClock
 */
class EloquentTimeClockRepository extends EloquentAbstractRepository implements TimeClockRepositoryInterface
{
    protected $sorters = [
        'clock_type' => [],
        'user.given_name' => [],
        'created_at' => [],
        'time_in' => [],
        'time_out' => [],
    ];

    /**
     * @param TimeClock $model
     */
    public function __construct(TimeClock $model)
    {
        $this->model = $model;
    }

    public function getWithUser($userId)
    {
        return $this->make()->with('User')->find($userId);
    }

    public function getOpenEntry($storeId, $userId)
    {
        return $this->makeWithStoreUser($storeId, $userId)->whereNull('time_out')->limit(1)->get();
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
        $query = $this->makeWithStoreUser($storeId, $userId);

        if (!is_null($from)) {
            /** @var Carbon $end */
            $end = \Time::carbon($to);
            $query->where('time_in', '>=', $from)->where('time_out', '<', $end->copy()->addDay()->format('Y-m-d'));
        }

        $paginator = $query->orderBy('time_in', 'DESC')->paginate($count);

        return $paginator;
    }

    /**
     * @param int $storeId
     * @param int $userId
     * @param string $startTime
     * @param string $endTime
     * @return mixed
     */
    public function getTimeClockEntriesBetweenTimes($storeId, $userId, $startTime, $endTime)
    {
        $query = $this->make();

        $query->where('store_id', '=', $storeId);

        if ($userId)
        {
            $query->where('user_id', '=', $userId);
        }

        $query->where('time_in', '>=', $startTime)->where('time_in', '<', $endTime);

        return $query->orderBy('time_in', 'desc')->get();
    }

    public function getStoreEntries($storeId, $startTime, $endTime)
    {
        return $this->make()->where('store_id', '=', $storeId)->where('time_in', '>=', $startTime)
            ->where('time_out', '<', $endTime)->orderBy('time_in', 'desc')->get();
    }

    protected function openEntry($storeId, $userId, $clockType)
    {
        return $this->create([
                'store_id' => $storeId,
                'user_id' => $userId,
                'clock_type' => $clockType,
                'time_in' => \Time::now()
            ]);
    }

    protected function closeEntry($storeId, $userId, $clockType)
    {
        $entry = $this->makeWithStoreUser($storeId, $userId)
            ->where('clock_type', '=', $clockType)->whereNull('time_out')
            ->first();

        $entry->time_out = \Time::now();
        $entry->save();
        return $entry;
    }

    protected function hasOpenEntry($storeId, $userId, $clockType)
    {
        $count = $this->makeWithStoreUser($storeId, $userId)->where('clock_type', '=', $clockType)
            ->whereNull('time_out')->count();
        return $count != 0;
    }

    public function clockIn($storeId, $userId)
    {
        return $this->openEntry($storeId, $userId, TimeClock::CLOCKTYPE_ONCLOCK);
    }

    public function clockOut($storeId, $userId)
    {
        return $this->closeEntry($storeId, $userId, TimeClock::CLOCKTYPE_ONCLOCK);
    }

    public function breakStart($storeId, $userId)
    {
        return $this->openEntry($storeId, $userId, TimeClock::CLOCKTYPE_ONBREAK);
    }

    public function breakEnd($storeId, $userId)
    {
        return $this->closeEntry($storeId, $userId, TimeClock::CLOCKTYPE_ONBREAK);
    }

    public function isUserClockedIn($storeId, $userId)
    {
        return $this->hasOpenEntry($storeId, $userId, TimeClock::CLOCKTYPE_ONCLOCK);
    }

    public function isUserOnBreak($storeId, $userId)
    {
        return $this->hasOpenEntry($storeId, $userId, TimeClock::CLOCKTYPE_ONBREAK);
    }
}