<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\MarketingLocation\MarketingLocationService;
use Illuminate\Support\Facades\DB;

class MarketingLocationController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    protected $service;

    public function __construct(MarketingLocationService $service)
    {
        $this->service = $service;
    }

    public function gps($latitude, $longitude)
    {
        $sql = "SELECT *, ( 3959 * acos( cos( radians({$latitude}) ) * cos( radians( marketing_locations.latitude ) )
            * cos( radians(marketing_locations.longitude) - radians({$longitude})) + sin(radians({$latitude}))
            * sin( radians(marketing_locations.latitude)))) AS distance
            FROM marketing_locations HAVING distance < 10 ORDER BY distance;";

        $result = DB::select(DB::raw($sql));

        return $this->data($result)->statusOk()->respond();
    }
}