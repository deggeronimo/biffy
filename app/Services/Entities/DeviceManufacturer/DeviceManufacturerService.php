<?php namespace Biffy\Services\Entities\DeviceManufacturer;

use Biffy\Entities\DeviceManufacturer\DeviceManufacturerRepositoryInterface;
use Biffy\Services\Entities\Service;
use Biffy\Services\Entities\WebsiteFilter\WebsiteFilterService;

class DeviceManufacturerService extends Service
{
    protected $websiteFilterService;

    public function __construct(DeviceManufacturerRepositoryInterface $repo, WebsiteFilterService $websiteFilterService)
    {
        $this->repo = $repo;

        $this->websiteFilterService = $websiteFilterService;
    }

}