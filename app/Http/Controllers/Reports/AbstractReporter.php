<?php namespace Biffy\Http\Controllers\Reports;

use Biffy\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Auth;

abstract class AbstractReporter extends ApiController
{
    public function doReport($reportType, $startTime, $endTime)
    {
        $storeId = Auth::user()->storeId();

        if (!preg_match('/([0-9]{4}-[0-9]{2}-[0-9]{2})/', $startTime)) {
            $startTime = date('Y-m-d H:i:s', $startTime);
            $endTime = date('Y-m-d H:i:s', $endTime);
        }

        $action = 'generate' . ucfirst(camel_case($reportType)) . 'Report';

        if(is_callable([ $this, $action ]))
        {
            $result = $this->$action($storeId, $startTime, $endTime);

            return $this->data($result)->statusOk()->respond();
        }
        else
        {
            return $this->data([])->statusNotFound()->respond();
        }
    }

    public function getMidnightThisMorning()
    {
        $timeStamp = time();

        $dateStamp = date('Y-m-d', $timeStamp);

        $midnightToday = strtotime($dateStamp);

        return $midnightToday;
    }

    public function getMidnightTonight()
    {
        return $this->getMidnightThisMorning() + 86400;
    }

    public function getMidnight28DaysAgo()
    {
        return $this->getMidnightThisMorning() - 2419200;
    }

    public function & getRecordByField( & $masterArray, $fieldName, $fieldValue, $defaultArray)
    {
        for ($i = 0; $i < count($masterArray); $i ++)
        {
            if ($masterArray[$i][$fieldName] == $fieldValue)
            {
                return $masterArray[$i];
            }
        }

        $defaultArray[$fieldName] = $fieldValue;
        $masterArray[] = $defaultArray;

        return $masterArray[count($masterArray)-1];
    }
}