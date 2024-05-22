<?php

namespace App\DtoBuilder\Task;

use App\Dto\Task\EditTaskDto;
use App\Entity\Task;

readonly class EditTaskDtoBuilder
{
    public static function fromDto(EditTaskDto $editTaskDto, Task &$task): void
    {
        $task->setTitle($editTaskDto->title);
        $task->setDescription($editTaskDto->description);
        $task->setPriority($editTaskDto->priority);
    }
}
