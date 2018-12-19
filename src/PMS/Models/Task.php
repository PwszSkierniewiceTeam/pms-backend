<?php
/**
 * Created by PhpStorm.
 * User: emilm
 * Date: 08.12.2018
 * Time: 22:50
 */

namespace PMS\Models;


class Task extends BaseModel
{
    /* @var UUID $id */
    public $id;
    /* @var string $name */
    public $name;
    /* @var string $projectId */
    public $projectId;
    /* @var string $type */
    public $type;
}