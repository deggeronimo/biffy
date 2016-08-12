<?php namespace Biffy\Http\Controllers\Helpers;

use Biffy\Entities\EloquentAbstractChildRepository;

trait SelectControllerHelper
{
    public function select()
    {
        $args = func_get_args();

        if($this->repo instanceof EloquentAbstractChildRepository) {
            $this->repo->parentId(array_pop($args) ?: null);
        }

        $result = $this->repo
            ->filterBy($this->input('filter'))
            ->sortBy($this->input('sorting'))->get();

        $keyName = $this->repo->selectKeyName;
        $valueName = $this->repo->selectValueName;

        $data = [];
        foreach($result as $item) {
            $data[] = [$keyName => $item->$keyName, $valueName => $item->$valueName];
        }
        return $this->data($data)->respond();
    }
}
