<?php

namespace App\Service\Task;

use App\Dto\Task\TaskDto;
use App\DtoBuilder\Task\TaskDtoBuilder;
use App\Entity\Task;
use App\Entity\User;
use App\Enum\Task\TaskStatus;
use App\Exception\AccessDeniedException;
use App\Exception\AllTaskMustBeCompletedException;
use App\Manager\TaskManager;
use App\Validator\Task\TaskAccessValidator;

class TaskCompleter
{
    public function __construct(
        private readonly TaskManager $taskManager,
    ) {
    }

    /**
     * @throws AccessDeniedException
     * @throws AllTaskMustBeCompletedException
     */
    public function complete(User $user, Task $task): TaskDto
    {
        TaskAccessValidator::validateUserAccess($user, $task);
        TaskAccessValidator::validateTaskComplete($task);
        $task->complete();
        $task->setStatus(TaskStatus::DONE);
        $this->taskManager->update($task);

        return TaskDtoBuilder::fromEntity($task);
    }
}
