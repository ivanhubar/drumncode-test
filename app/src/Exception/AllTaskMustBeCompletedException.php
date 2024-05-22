<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class AllTaskMustBeCompletedException extends \Exception
{
    public function __construct()
    {
        parent::__construct('all_tasks_must_be_completed', Response::HTTP_CONFLICT);
    }
}
