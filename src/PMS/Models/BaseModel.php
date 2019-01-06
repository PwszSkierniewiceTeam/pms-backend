<?php

namespace PMS\Models;

abstract class BaseModel
{
    public function __construct($object = [])
    {
        if ($object) {
            foreach ($object as $property => &$value) {
                $this->$property = &$value;
                unset($object->$property);
            }
        }

    }
}
