<?php namespace Biffy\Commands\Inventory;

use Biffy\Commands\AbstractCommand;
use Biffy\Commands\CommandHandler;
use Biffy\Entities\Inventory\EloquentInventoryRepository;
use Biffy\Entities\Inventory\InventoryRepositoryInterface;
use Biffy\Entities\StoreItem\EloquentStoreItemRepository;
use Biffy\Entities\StoreItem\StoreItemRepositoryInterface;
use Biffy\Services\Entities\CommandFailedException;
use Biffy\Services\Entities\RollbackFailedException;

/**
 * Class AddInventoryCommandHandler
 * @package Biffy\Commands\Inventory
 */
class AddInventoryCommandHandler implements CommandHandler
{
    /**
     * @var EloquentInventoryRepository
     */
    private $inventoryRepository;

    /**
     * @var EloquentStoreItemRepository
     */
    private $storeItemRepository;

    /**
     * @param InventoryRepositoryInterface $inventoryRepository
     * @param StoreItemRepositoryInterface $storeItemRepository
     */
    public function __construct(InventoryRepositoryInterface $inventoryRepository, StoreItemRepositoryInterface $storeItemRepository)
    {
        $this->inventoryRepository = $inventoryRepository;
        $this->storeItemRepository = $storeItemRepository;
    }

    /**
     * @param AbstractCommand $command
     *
     * @throws CommandFailedException
     */
    public function handle(AbstractCommand $command)
    {
        $command->saved_id = $this->inventoryRepository->create($command->attributes);

        $storeItem = $this->storeItemRepository->find($command->attributes['store_item_id']);
        $storeItem->stock += $command->increment;
        $storeItem->save ();

        $command->result = $storeItem;
    }

    /**
     * @param AbstractCommand $command
     *
     * @throws RollbackFailedException
     */
    public function handleRollback(AbstractCommand $command)
    {
        $storeItem = $this->storeItemRepository->find($command->attributes['store_item_id']);
        $storeItem->stock -= $command->increment;
        $storeItem->save ();

        $this->inventoryRepository->delete($command->saved_id);
    }
}