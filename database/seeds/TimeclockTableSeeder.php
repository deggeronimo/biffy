<?php

use Biffy\Entities\Store\Store;
use Biffy\Entities\TimeClock\TimeClock;
use Biffy\Entities\User\User;
use Carbon\Carbon;

class TimeClockTableSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        $storeId = Store::where('name', '=', 'Dev')->first()->id;
        $users = User::whereIn('email', ['b.burr@ubreakifix.com', 'c.florin@ubreakifix.com'])->get();
        $today = new Carbon('now', 'America/New_York');
        $start = $today->copy()->subWeek();
        $end = $today->copy()->addWeek();

        foreach ($users as $user) {
            $userId = $user->id;
            $current = $start->copy();

            while ($current->lte($end)) {
                $entry = ['user_id' => $userId, 'store_id' => $storeId];
                if ($current->day % 3 !== 0) {
                    $entry['time_in'] = $current->format('Y-m-d') . ' 9:30:00';
                    $entry['time_out'] = $current->format('Y-m-d') . ' 12:30:00';
                    $entry['clock_type'] = 1;

                    Timeclock::create($entry);

                    $entry['time_in'] = $current->format('Y-m-d') . ' 12:30:00';
                    $entry['time_out'] = $current->format('Y-m-d') . ' 13:00:00';
                    $entry['clock_type'] = 2;

                    Timeclock::create($entry);

                    $entry['time_in'] = $current->format('Y-m-d') . ' 13:00:00';
                    $entry['time_out'] = $current->format('Y-m-d') . ' 19:00:00';
                    $entry['clock_type'] = 1;

                    Timeclock::create($entry);
                }
                $current->addDay();
            }
        }
    }
}
