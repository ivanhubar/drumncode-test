<?php

namespace App\Enum\Task;

enum TaskStatus: string
{
    case TODO = 'todo';
    case DONE = 'done';
}
