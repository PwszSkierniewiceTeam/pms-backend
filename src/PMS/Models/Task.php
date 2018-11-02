<?php
/**
 * Created by PhpStorm.
 * User: Grimneon
 * Date: 29.10.2018
 * Time: 23:35
 */

namespace PMS\Models;


class Task extends BaseModel
{
    /* @var UUID $id */
    public $id;
    /* @var UUID $projectId */
    public $projectId;
    /* @var User $assignedUser */
    public $assignedUser;
    /* @var string $name */
    public $name;
    /* @var string $description */
    public $description;
    /* @var TaskType $type */
    public $type;
}