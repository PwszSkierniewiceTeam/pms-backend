<?php

namespace PMS\Models;

class Token extends BaseModel
{
    public $value;
    public $userId;
    public $dateCreated;
    public $dateExpiration;
}
