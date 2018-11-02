<?php
/**
 * Created by PhpStorm.
 * User: Grimneon
 * Date: 29.10.2018
 * Time: 21:58
 */

namespace PMS\Models;


class Project extends BaseModel
{
    /* @var UUID $id */
    public $id;
    /* @var string $name */
    public $name;
    /* @var string $description */
    public $description;
    /* @var date-time $startDate */
    public $startDate;
    /* @var date-time $endDate */
    public $endDate;
    /* @var ProjectUserRole $userRole */
    public $userRole;
}