<?php namespace Biffy\Entities\Item;

use Biffy\Entities\AbstractRepositoryInterface;

interface ItemRepositoryInterface extends AbstractRepositoryInterface
{
    /**
     * @deprecated
     * @param $itemNumber
     * @return mixed
     */
    public function findByNumber($itemNumber);

    public function getGlobalIds();

    public function edit($itemId, $name, $number);

    public function search($term);
}