<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class TaskCompletedException extends \Exception
{
    public function __construct()
    {
        parent::__construct('task_already_completed', Response::HTTP_BAD_REQUEST);
    }
}
