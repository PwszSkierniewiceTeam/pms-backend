<?php


namespace PMS\Models;


use PMS\Enums\ProjectUserRole;


class Project extends BaseModel
{
    /* @var UUID $id */
    public $id;
    /* @var string $name */
    public $name;
    // /* @var string $description */
    // public $description;
    /* @var date-time $startDate */
    public $startDate;
    /* @var date-time $endDate */
    public $endDate;
    /* @var ProjectUserRole $userRole*/
    public $userRole;
}