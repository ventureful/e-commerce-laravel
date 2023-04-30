<?php

namespace App\Traits\Models;

trait FillableFields
{
    protected $storagePath = '';

    /**
     * @return array
     */
    public static function getFillableFields()
    {
        return (new static())->getFillable();
    }

    /**
     * @return mixed
     */
    public function getRecordTitle()
    {
        return $this->name ?? $this->id;
    }
}
