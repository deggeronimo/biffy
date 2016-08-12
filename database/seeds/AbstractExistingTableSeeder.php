<?php

use Illuminate\Support\Facades\DB;

abstract class AbstractExistingTableSeeder extends \Illuminate\Database\Seeder
{
    protected $idFieldName = 'id';

    protected $joins = [];

    protected $mappings = [];

    protected $model = null;

    protected $select = '*';

    protected $service = null;

    protected $sourceTable = null;

    public function run()
    {
        $use = $this->service ? : $this->model;

        if (is_null($use))
        {
            return;
        }

        $joinSet = '';

        foreach ($this->joins as $join)
        {
            $joinSet .= 'left join ' . $join . ' ';
        }

        $sql = "SELECT {$this->select}, {$this->sourceTable}.{$this->idFieldName} FROM {$this->sourceTable} {$joinSet} ORDER BY {$this->orderBy()}";

        $results = DB::select(DB::raw($sql));

        $newRow = [];
        foreach ($results as $result)
        {
            if (is_null($this->beforeInsert($result)))
            {
                continue;
            }

            foreach ($this->mappings as $key => $value)
            {
                $newRow[$value] = $result->$key;
            }

            $modelItem = $use->create($newRow);

            if ($this->afterInsert($modelItem))
            {
                $modelItem->save();
            }
        }

        $this->complete();
    }

    public function afterInsert( & $newRow)
    {
        //Overrides for this function should return true if $newRow needs to be saved again
        return false;
    }

    public function beforeInsert( & $oldRow)
    {
        return $oldRow;
    }

    public function complete() {}

    protected function orderBy()
    {
        return $this->sourceTable . '.'. $this->idFieldName;
    }
}