<?php namespace Biffy\Entities\CustomerContact;

use Biffy\Entities\AbstractRepositoryInterface;

/**
 * Interface CustomerContactRepositoryInterface
 * @package Biffy\Entities\CustomerContact
 */
interface CustomerContactRepositoryInterface extends AbstractRepositoryInterface
{
    /**
     * @param $customerId
     * @return mixed
     */
    public function getContactsForCustomer($customerId);
}