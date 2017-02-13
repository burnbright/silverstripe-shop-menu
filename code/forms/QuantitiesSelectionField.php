<?php

/**
 * Select multiple quantities in a list
 */
class QuantitiesSelectionField extends FormField
{
    
    protected $list;

    public function __construct($name, $title, $list)
    {
        $this->list = $list;
        parent::__construct($name, $title);
    }

    public function getDataList()
    {
        return $this->list;
    }

    //supply numeric fields, and set min values
    //TODO: validate values

    public function getSumQuantities()
    {
        if (is_array($this->value)) {
            return array_sum($this->value);
        }
        return 0;
    }

    public function getQuantities()
    {
        if (is_array($this->value)) {
            return array_filter($this->value);
        }
        return array();
    }
}
