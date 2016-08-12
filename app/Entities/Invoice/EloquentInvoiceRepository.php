<?php namespace Biffy\Entities\Invoice;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentInvoiceRepository extends EloquentAbstractRepository implements InvoiceRepositoryInterface
{
    protected $filters = [
        'search' => [ '(customers.full_name like \'?\' or companies.name like \'?\')', '%:value%', '%:value%' ]
    ];

    /**
     * @var array
     */
    protected $sorters = [
        'subtotal' => [],
        'taxes' => [],
        'payments' => [],
    ];

    public function __construct(Invoice $model)
    {
        $this->model = $model;

        $this->with([ 'company', 'customer', 'invoicePayments' ]);
    }

    protected function preGet()
    {
        if (array_key_exists('search', $this->filterBy))
        {
            $this->query->leftJoin('customers', 'customers.id', '=', 'invoices.customer_id');
            $this->query->leftJoin('companies', 'companies.id', '=', 'invoices.company_id');
        }
    }
}