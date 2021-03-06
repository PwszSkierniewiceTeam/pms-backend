<?php
/**
 * Created by PhpStorm.
 * User: emilm
 * Date: 08.12.2018
 * Time: 22:50
 */

namespace PMS\Models;


use PMS\Enums\TaskStatus;
use PMS\Enums\TaskType;


class Task extends BaseModel
{
    /* @var UUID $id */
    public $id;
    /* @var string $name */
    public $name;
    /* @var string $projectId */
    public $projectId;
    /* @var TaskType $type */
    public $type;
    /* @var TaskStatus $status */
    public $status;
    /* @var User $assignedUser */
    public $assignedUser;
}
