<?php namespace Biffy\Entities\Item;

use Biffy\Entities\EloquentAbstractRepository;

/**
 * Class EloquentItemRepository
 * @package Biffy\Entities\Item
 */
class EloquentItemRepository extends EloquentAbstractRepository implements ItemRepositoryInterface
{
    protected $sorters = [
        'distro_price' => [],
        'item_number' => [],
        'labor_cost' => [],
        'name' => [],
        'unit_price' => []
    ];

    protected $filters = [
        'search' => [ 'item_number LIKE ? OR name LIKE ?', '%:value%', '%:value%' ]
    ];

    /**
     * @param Item $model
     */
    public function __construct(Item $model)
    {
        $this->model = $model;

        $this->with(['deviceType']);
    }

    /**
     * @param $itemNumber
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function findByNumber($itemNumber)
    {
        return $this->findFirstBy('number', $itemNumber);
    }

    /**
     * @param $itemId
     * @param $name
     * @param $number
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function edit($itemId, $name, $number)
    {
        $item = $this->find($itemId);
        $item->name = $name;
        $item->number = $number;
        $item->save();

        return $item;
    }

    /**
     * @return array
     */
    public function getGlobalIds()
    {
        return $this->findAllBy('global', 1)->modelKeys();
    }

    /**
     * @param $term
     */
    public function search($term)
    {
        // TODO: Implement search() method.
    }
}