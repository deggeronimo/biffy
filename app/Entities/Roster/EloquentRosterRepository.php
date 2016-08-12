<?php namespace Biffy\Entities\Roster;

use Biffy\Entities\EloquentAbstractRepository;

/**
 * Class EloquentRosterRepository
 * @package Biffy\Entities\Roster
 */
class EloquentRosterRepository extends EloquentAbstractRepository implements RosterRepositoryInterface
{
    /**
     * @var array
     */
    protected $sorters = [
        'start_time' => [],
    ];

    /**
     * @var array
     */
    protected $filters = [
        'id' => ['id = ?', ':value'],
        'store_id' => ['store_id = ?', ':value'],
        'employee_id' => ['employee_id = ?', ':value'],
        'start_time_begin' => ['start_time >= ?', ':value'], // eg: 18-Jan-2015 is Sunday/first day of week then we search for start_time_begin=18-Jan-2015 with value >= 2015-01-18 0000
        'start_time_end' => ['start_time < ?', ':value'], // eg: 25-Jan-2015 is Sunday/first day of next week then we search for start_time_end=25-Jan-2015 with value < 2015-01-25 0000
        'end_time_begin' => ['end_time >= ?', ':value'],
        'end_time_end' => ['end_time < ?', ':value'],
    ];

    /**
     * @param Roster $model
     */
    public function __construct(Roster $model)
    {
        $this->model = $model;
    }

}
