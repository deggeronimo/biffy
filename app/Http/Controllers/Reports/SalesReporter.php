<?php namespace Biffy\Http\Controllers\Reports;

use Biffy\Entities\Sale\SaleRepositoryInterface;
use Biffy\Services\Entities\Sale\SaleService;
use Carbon\Carbon;

class SalesReporter extends AbstractReporter
{
    protected $service;

    protected $saleRepo;

    public function __construct(SaleService $service, SaleRepositoryInterface $saleRepo)
    {
        $this->service = $service;

        $this->saleRepo = $saleRepo;
    }

    /**
     * @param $storeId
     * @param $startTime
     * @param $endTime
     * @return array
     */
    public function generateDetailedReport($storeId, $startTime, $endTime)
    {
        $report = [];

        $query = $this->saleRepo->with(['customer'])->make();
        $query->where('store_id', '=', $storeId)->where('created_at', '>=', $startTime)->where('created_at', '<=', $endTime);
        $saleList = $query->orderBy('created_at', 'desc')->get();

        foreach ($saleList as $sale)
        {
            $sale['profit'] = 0.0;
            $report[] = $sale;
        }

        return $report;
    }

    /**
     * @param $storeId
     * @param $startTime
     * @param $endTime
     * @return array
     */
    public function generateSummaryReport($storeId, $startTime, $endTime)
    {
        static $blankDayRecord = [
            'day' => '',
            'subtotal' => 0.0,
            'taxes' => 0.0,
            'profit' => 0.0,
            'payments' => 0.0,
            'adjustments' => 0.0,
            'trade_credit' => 0.0
        ];

        $report = [];

        $query = $this->saleRepo->make();
        $query->where('store_id', '=', $storeId)->where('created_at', '>=', $startTime)->where('created_at', '<=', $endTime);
        $saleList = $query->orderBy('created_at', 'desc')->get();

        foreach ($saleList as $sale)
        {
            $saleCreatedAt = Carbon::parse($sale['created_at']);

            $currentDay = & $this->getRecordByField($report, 'day', $saleCreatedAt->toDateString(),
                array_merge($blankDayRecord, [ 'day' => $saleCreatedAt->toDateString() ]));

            $currentDay['subtotal'] += $sale['subtotal'];
            $currentDay['taxes'] += $sale['taxes'];
            $currentDay['payments'] += $sale['payments'];
            $currentDay['adjustments'] += $sale['adjustments'];
            $currentDay['trade_credit'] += $sale['trade_credit'];
            $currentDay['profit'] += 0.0;//$sale['profit'];
        }

        return $report;
    }

}