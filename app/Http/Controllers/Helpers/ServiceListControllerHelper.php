<?php namespace Biffy\Http\Controllers\Helpers;

use Biffy\Facades\StoreConfig;

trait ServiceListControllerHelper
{
    /**
     * @return mixed
     */
    public function index()
    {
        $filter = $this->input('filter');
        $sorting = $this->input('sorting');

        if (!is_null($this->input('all')))
        {
            $result = $this->service->getList($filter, $sorting);

            $morph = $this->getMorph('list');

            if (!is_null($morph))
            {
                $result = $this->morph([ 'results' => $result->toArray() ], $morph);
                // todo remove need for array_key_exists check (handle above statement better)
                return $this->data(array_key_exists('results', $result) ? $result['results'] : [])->respond();
            }
            else
            {
                return $this->data($result)->respond();
            }
        }
        else
        {
            //@todo Make new config setting for count per page
            $perPage = StoreConfig::get('results-per-page');
            $perPage = (is_null($perPage)?10:$perPage);

            $count = $this->input('count', $perPage);
            $page = $this->input('page', 1);

            $result = $this->service->getIndex($count, $page, $filter, $sorting);

            return $this->data($result->toArray()['data'])->paginator($result)->respond();
        }
    }
}
