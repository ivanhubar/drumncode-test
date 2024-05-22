<?php

namespace App\Service\Task;

use App\Entity\Task;
use App\Entity\User;
use App\Exception\AccessDeniedException;
use App\Exception\TaskCompletedException;
use App\Manager\TaskManager;
use App\Validator\Task\TaskAccessValidator;

class TaskDeleter
{
    public function __construct(
        private readonly TaskManager $taskManager,
    ) {
    }

    /**
     * @throws TaskCompletedException
     * @throws AccessDeniedException
     */
    public function delete(Task $task, User $user): void
    {
        TaskAccessValidator::validateUserAccess($user, $task);
        TaskAccessValidator::validateTaskDelete($task);

        $this->taskManager->delete($task);
    }
}
