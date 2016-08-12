<?php namespace Biffy\Entities\CustomerNote;

use Biffy\Entities\AbstractRepositoryInterface;

/**
 * Interface CustomerNoteRepositoryInterface
 * @package Biffy\Entities\CustomerNote
 */
interface CustomerNoteRepositoryInterface extends AbstractRepositoryInterface
{
    /**
     * @param $customerId
     * @return mixed
     */
    public function getNotesForCustomer($customerId);
}