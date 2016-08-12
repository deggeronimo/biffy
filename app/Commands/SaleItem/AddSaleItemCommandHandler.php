<?php namespace Biffy\Commands\SaleItem;

use Biffy\Commands\AbstractCommand;
use Biffy\Commands\CommandHandler;
use Biffy\Entities\Sale\SaleRepositoryInterface;
use Biffy\Entities\SaleItem\SaleItemRepositoryInterface;
use Biffy\Entities\SaleItemTax\SaleItemTaxRepositoryInterface;
use Biffy\Entities\StoreTax\StoreTaxRepositoryInterface;
use Biffy\Services\Entities\CommandFailedException;
use Biffy\Services\Entities\RollbackFailedException;
use Illuminate\Support\Facades\Auth;

/**
 * Class AddSaleItemCommandHandler
 * @package Biffy\Commands\SaleItem
 */
class AddSaleItemCommandHandler implements CommandHandler
{
    /**
     * @var SaleItemRepositoryInterface
     */
    private $saleItemRepository;

    /**
     * @var SaleRepositoryInterface
     */
    private $saleRepository;

    /**
     * @var SaleItemTaxRepositoryInterface
     */
    private $saleItemTaxRepository;

    /**
     * @var StoreTaxRepositoryInterface
     */
    private $storeTaxRepository;

    public function __construct(SaleItemRepositoryInterface $saleItemRepository, SaleRepositoryInterface $saleRepository,
                                SaleItemTaxRepositoryInterface $saleItemTaxRepository, StoreTaxRepositoryInterface $storeTaxRepository)
    {
        $this->saleItemRepository = $saleItemRepository;
        $this->saleRepository = $saleRepository;
        $this->saleItemTaxRepository = $saleItemTaxRepository;
        $this->storeTaxRepository = $storeTaxRepository;
    }

    /**
     * @param AbstractCommand $command
     *
     * @throws CommandFailedException
     */
    public function handle(AbstractCommand $command)
    {
        $command->result = $this->saleItemRepository->create(
            [
                'sale_id' => null,
                'work_order_id' => null,
                'inventory_id' => $command->inventory_id,
                'price' => $command->price,
                'labor_cost' => $command->labor_cost,
                'discount' => $command->discount,
                'on_receipt' => $command->on_receipt,
                'tax_exempt' => $command->tax_exempt
            ]
        );

        if (isset($command->sale_id))
        {
            $command->result->sale_id = $command->sale_id;
            $command->result->save();
        }

        if (isset($command->work_order_id))
        {
            $command->result->work_order_id = $command->work_order_id;
            $command->result->save();
        }

        // get store taxes
        $taxIds = $this->storeTaxRepository->getTaxIds(Auth::user()->storeId());

        // create sale item tax for each store tax
        foreach ($taxIds as $taxId)
        {
            $this->saleItemTaxRepository->create([
                'sale_item_id' => $command->result->id,
                'store_tax_id' => $taxId->id
            ]);
        }
    }

    /**
     * @param AbstractCommand $command
     *
     * @throws RollbackFailedException
     */
    public function handleRollback(AbstractCommand $command)
    {
        $this->saleItemRepository->delete($command->result->id);

        $this->saleItemTaxRepository->deleteTaxItemsWithSaleItemId($command->result->id);
    }
}