<?php

namespace App\Service\Task;

use App\Dto\Task\EditTaskDto;
use App\Dto\Task\TaskDto;
use App\DtoBuilder\Task\EditTaskDtoBuilder;
use App\DtoBuilder\Task\TaskDtoBuilder;
use App\Entity\Task;
use App\Entity\User;
use App\Exception\AccessDeniedException;
use App\Manager\TaskManager;
use App\Validator\Task\TaskAccessValidator;

class TaskEditor
{
    public function __construct(
        private readonly TaskManager $taskManager,
    ) {
    }

    /**
     * @throws AccessDeniedException
     */
    public function edit(Task $task, User $user, EditTaskDto $editTaskDto): TaskDto
    {
        TaskAccessValidator::validateUserAccess($user, $task);
        EditTaskDtoBuilder::fromDto($editTaskDto, $task);

        $this->taskManager->update($task);

        return TaskDtoBuilder::fromEntity($task);
    }
}
