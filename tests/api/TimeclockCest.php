<?php

require_once 'ApiCest.php';

// todo test entries, history, and details - verify hours calculations
class TimeclockCest extends ApiCest
{
    public function getPayPeriod(ApiTester $I)
    {
        $I->sendGET('timeclock/pay-period');
        $I->seeResponseContainsJson(['day' => 4]);
    }
    
    public function initialClockState(ApiTester $I)
    {
        $this->testClockStatus($I, false, false);
    }

    /*
     * CAN clockIn
     */
    public function clockStateNotClockedIn(ApiTester $I)
    {
        $this->testClock($I, 'clock-out', true);
        $this->testClock($I, 'break-start', true);
        $this->testClock($I, 'break-end', true);
    }

    public function clockIn(ApiTester $I)
    {
        $this->testClock($I, 'clock-in');
        $this->testClockStatus($I, true, false);
    }

    /*
     * CAN clockOut, breakStart
     */
    public function clockStateClockedIn(ApiTester $I)
    {
        $this->testClock($I, 'clock-in', true);
        $this->testClock($I, 'break-end', true);
    }

    public function breakStart(ApiTester $I)
    {
        $this->testClock($I, 'break-start');
        $this->testClockStatus($I, false, true);
    }

    /*
     * CAN breakEnd
     */
    public function clockStateOnBreak(ApiTester $I)
    {
        $this->testClock($I, 'clock-in', true);
        $this->testClock($I, 'clock-out', true);
        $this->testClock($I, 'break-start', true);
    }

    public function breakEnd(ApiTester $I)
    {
        $this->testClock($I, 'break-end');
        $this->testClockStatus($I, true, false);
    }

    /*
     * CAN clockOut, breakStart
     */
    public function clockStateReturnedFromBreak(ApiTester $I)
    {
        $this->testClock($I, 'clock-in', true);
        $this->testClock($I, 'break-end', true);
    }

    public function clockOut(ApiTester $I)
    {
        $this->testClock($I, 'clock-out');
        $this->testClockStatus($I, false, false);
    }

    /*
     * CAN clockIn
     */
    public function clockStateClockedOut(ApiTester $I)
    {
        $this->testClock($I, 'clock-out', true);
        $this->testClock($I, 'break-start', true);
        $this->testClock($I, 'break-end', true);
    }

    protected function testClock(ApiTester $I, $what, $shouldError = false)
    {
        $I->sendPOST("timeclock/{$what}");
        $I->seeResponseCodeIs($shouldError ? 409 : 200);
    }

    protected function testClockStatus(ApiTester $I, $clockedIn, $onBreak)
    {
        $I->sendGET('timeclock/entries');
        $I->seeResponseContainsJson(['clockedIn' => $clockedIn, 'onBreak' => $onBreak]);
    }
}
