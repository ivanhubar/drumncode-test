<?php

namespace App\DtoBuilder\Task;

use App\Dto\Task\CreateTaskDto;
use App\Entity\Task;
use App\Entity\User;

class CreateTaskDtoBuilder
{
    public static function fromDto(CreateTaskDto $dto, User $user, ?Task $parentTask = null): Task
    {
        $task = new Task();
        $task->setTitle($dto->title);
        $task->setDescription($dto->description);
        $task->setPriority($dto->priority);
        $task->setUser($user);
        $task->setParent($parentTask);

        return $task;
    }
}
