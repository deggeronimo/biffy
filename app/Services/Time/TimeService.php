<?php namespace Biffy\Services\Time;

use Biffy\Services\Config\StoreConfigService;
use Carbon\Carbon;
use \DateTimeZone;

class TimeService
{
    /**
     * @var StoreConfigService
     */
    private $storeConfig;

    /**
     * @var DateTimeZone
     */
    private $timezone;

    public function __construct(StoreConfigService $storeConfigService)
    {
        $this->storeConfig = $storeConfigService;
        $this->timezone = $this->storeConfig->get('timezone', 'America/New_York');
    }

    /**
     * @return Carbon
     */
    public function now()
    {
        return Carbon::now($this->timezone);
    }

    public function fileTimestamp()
    {
        return $this->now()->format('YmdHis');
    }

    /**
     * @param $start
     * @param $end
     * @param bool $decimal True to return a decimal value, otherwise will be truncated according to Carbon::diffInHours
     * @return float|int
     */
    public function diffInHours($start, $end, $decimal = true)
    {
        if (is_null($end)) {
            return null;
        }

        $start = $this->carbon($start);
        $end = $this->carbon($end);

        if ($decimal) {
            $seconds = $end->diffInSeconds($start);
            return $this->secondsToHours($seconds);
        }

        return $end->diffInHours($start);
    }

    public function day($datetime)
    {
        $carbon = Carbon::parse($datetime);
        return $carbon->format('Y-m-d');
    }

    public function carbon($time, $timezone = null)
    {
        if (is_null($timezone)) {
            $timezone = $this->timezone;
        }
        return new Carbon($time, $timezone);
    }

    public function secondsToHours($seconds)
    {
        return round($seconds / Carbon::SECONDS_PER_MINUTE / Carbon::MINUTES_PER_HOUR, 2);
    }

    public function strToMs($time)
    {
        return strtotime($time) * 1000;
    }
} 