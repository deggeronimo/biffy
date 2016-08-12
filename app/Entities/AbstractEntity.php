<?php

namespace Biffy\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression;

/**
 * Class AbstractEntity
 * @package Biffy\Entities
 */
abstract class AbstractEntity extends Model
{
    protected $strings = [
    ];

    /**
     * @param $query
     * @return mixed
     */
    public function scopeStrings($query)
    {
        if (empty($query->columns))
        {
            $query->select($this->getTable().".*");
        }

        $languageId = \LanguageTranslator::languageId();

        foreach ($this->strings as $stringAttribute)
        {
            $otherTable = "ls_{$stringAttribute}";

            $foreignKey = "{$stringAttribute}_language_key_id";

            $query->addSelect(new Expression("`{$otherTable}`.`string` as `{$stringAttribute}`"));

            if ($this->table == '')
            {
                echo(get_class($this));
            }

            $query->leftJoin("language_strings as {$otherTable}", "{$otherTable}.language_key_id", '=', "{$this->table}.{$foreignKey}");
            $query->where("{$otherTable}.language_id", '=', $languageId);
        }

        return $query;
    }

    public function getStrings()
    {
        return $this->strings;
    }
}