<?php namespace Biffy\Http\Controllers\Reports;

use Biffy\Entities\TimeClock\TimeClock;
use Biffy\Services\Entities\TimeClock\TimeClockService;
use Biffy\Services\Entities\User\UserService;
use Carbon\Carbon;

class TimeClockReporter extends AbstractReporter
{
    protected $service;
    protected $userService;

    protected $timeClockService;

    public function __construct(TimeClockService $service, UserService $userService, TimeClockService $timeClockService)
    {
        $this->service = $service;
        $this->userService = $userService;

        $this->timeClockService = $timeClockService;
    }

    public function generateDetailedReport($storeId, $startTime, $endTime)
    {
        /** @var Carbon $end */
        $end = \Time::carbon($endTime);
        /** @var \Illuminate\Database\Eloquent\Collection $entries */
        $entries = $this->timeClockService->getStoreEntries($storeId, $startTime, $end->copy()->addDay()->format('Y-m-d'));

        if (!$entries->count()) {
            return [];
        }

        $rawData = [];
        foreach ($entries as $entry) {
            $rawData[$entry->user_id][\Time::day($entry->time_in)][] = [
                'type' => $entry->clock_type,
                'hours' => \Time::diffInHours($entry->time_in, $entry->time_out)
            ];
        }

        $userIds = array_keys($rawData);
        /** @var UserService $userService */
        $users = $this->userService->getUsers($userIds);
        $users = array_combine(array_map(function ($val) {
            return $val['id'];
        }, $users), array_map(function ($val) {
            return $val['name'];
        }, $users));

        /** @var Carbon $start */
        $start = \Time::carbon($startTime);
        $payPeriodStartDay = \StoreConfig::get('pay-period-start', Carbon::THURSDAY);

        if ($start->dayOfWeek != $payPeriodStartDay)
        {
            $start = $start->previous($payPeriodStartDay);
        }

        $data = [];
        foreach ($rawData as $userId => $userData) {
            $current = $start->copy();
            $data[$userId] = [
                'user_id' => $userId,
                'name' => $users[$userId],
                'workHours' => 0,
                'breakHours' => 0,
                'dailyOvertime' => 0,
                'weeklyOvertime' => 0
            ];

            $weekWorkHours = 0;
            while ($current->lte($end)) {
                $day = $current->format('Y-m-d');
                if (array_key_exists($day, $userData)) {
                    $dayData = $userData[$day];
                    $dayHours = [
                        TimeClock::CLOCKTYPE_ONCLOCK => 0,
                        TimeClock::CLOCKTYPE_ONBREAK => 0
                    ];
                    foreach ($dayData as $entry) {
                        $dayHours[$entry['type']] += $entry['hours'];
                    }

                    $weekWorkHours += $dayHours[TimeClock::CLOCKTYPE_ONCLOCK];
                    $data[$userId]['workHours'] += $dayHours[TimeClock::CLOCKTYPE_ONCLOCK];
                    $data[$userId]['breakHours'] += $dayHours[TimeClock::CLOCKTYPE_ONBREAK];
                    $dayOvertime = $dayHours[TimeClock::CLOCKTYPE_ONCLOCK] - TimeClock::DAY_HOURS_BEFORE_OVERTIME;
                    $data[$userId]['dailyOvertime'] += $dayOvertime > 0 ? $dayOvertime : 0;
                }

                $current->addDay();

                // move to next week
                if (!$current->lte($end) || $current->dayOfWeek == $payPeriodStartDay) {
                    $weekOvertime = $weekWorkHours - TimeClock::WEEK_HOURS_BEFORE_OVERTIME;
                    $data[$userId]['weeklyOvertime'] += $weekOvertime > 0 ? $weekOvertime : 0;
                    $weekWorkHours = 0;
                }
            }
        }

        return array_values($data);
    }
}