<?php namespace Biffy\Http\Controllers;

use Biffy\Entities\MarketingRun\MarketingRunRepositoryInterface;

class MarketingRunStateController extends ApiController
{
    protected $repo;

    public function __construct(MarketingRunRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function show($id)
    {
        $marketingRun = $this->repo->firstByAttributes([ 'stopped' => '0' ]);

        if (is_null($marketingRun))
        {
            return $this->data(['state' => 'none'])->respond();
        }

        if ($marketingRun->stopped == 0)
        {
            return $this->data(['state' => 'running', 'id' => $marketingRun->id])->respond();
        }

        return $this->data(['state' => 'none'])->respond();
    }
}