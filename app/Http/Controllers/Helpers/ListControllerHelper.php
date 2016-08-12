<?php namespace Biffy\Http\Controllers\Helpers;

use Biffy\Entities\EloquentAbstractChildRepository;
use Biffy\Facades\StoreConfig;

trait ListControllerHelper
{
    public function index()
    {
        $perPage = StoreConfig::get('results-per-page');
        $perPage = (is_null($perPage)?10:$perPage);

        $count = $this->input('count', $perPage);
        $page = $this->input('page', 1);

        $args = func_get_args();

        // Assuming, child repositories have parent, model and childRelation pre-defined through repo constructor. See EloquentCompanyContactRepository
        // If repo is a child repo we pass last argument as parentId to fully establish relationship
        if($this->repo instanceof EloquentAbstractChildRepository) {
            $this->repo->parentId(array_pop($args) ?: null);
        }

        $result = $this->repo
            ->paginate($count, $page)
            ->filterBy($this->input('filter'))
            ->sortBy($this->input('sorting'))->get();

        return $this->data($result->toArray()['data'])->paginator($result)->respond();
    }
}
