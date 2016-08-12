<?php namespace Biffy\Services\Entities\CompanyInstructions;

use Biffy\Entities\CompanyInstructions\CompanyInstructionsRepositoryInterface;
use Biffy\Services\Entities\Service;

class CompanyInstructionsService extends Service
{
    public function __construct(CompanyInstructionsRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
}