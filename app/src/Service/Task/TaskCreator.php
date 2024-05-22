<?php

namespace App\Service\Task;

use App\Dto\Task\CreateTaskDto;
use App\Dto\Task\TaskDto;
use App\DtoBuilder\Task\CreateTaskDtoBuilder;
use App\DtoBuilder\Task\TaskDtoBuilder;
use App\Entity\User;
use App\Manager\TaskManager;

class TaskCreator
{
    public function __construct(
        private readonly TaskManager $taskManager,
    ) {
    }

    public function create(User $user, CreateTaskDto $createTaskDto): TaskDto
    {
        $parentTask = is_null($createTaskDto->parentId) ? null : $this->taskManager->findById($createTaskDto->parentId);

        $task = CreateTaskDtoBuilder::fromDto($createTaskDto, $user, $parentTask);
        $this->taskManager->create($task);

        return TaskDtoBuilder::fromEntity($task);
    }
}
