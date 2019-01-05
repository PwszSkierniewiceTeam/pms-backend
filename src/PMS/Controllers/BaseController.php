<?php

namespace PMS\Controllers;

use Awurth\SlimValidation\Validator;
use PDO;
use Slim\Http\Request;
use Slim\Http\Response;

abstract class BaseController
{
    protected $db;
    protected $validator;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->validator = new Validator();
    }

    abstract public function handleRequest(Request $request, Response $response): Response;
}
