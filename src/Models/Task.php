<?php

namespace Models;

class Task
{
    /* @var UUID $assignedUserId */
    private $assignedUserId;
    /* @var string $description */
    private $description;
    /* @var UUID $id */
    private $id;
    /* @var string $name */
    private $name;
    /* @var UUID $projectId */
    private $projectId;
    /* @var \\Models\TaskType $type */
    private $type;
}
