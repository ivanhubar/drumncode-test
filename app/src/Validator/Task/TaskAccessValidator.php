<?php

namespace App\Validator\Task;

use App\Entity\Task;
use App\Entity\User;
use App\Exception\AccessDeniedException;
use App\Exception\AllTaskMustBeCompletedException;
use App\Exception\TaskCompletedException;

class TaskAccessValidator
{
    /**
     * @throws AccessDeniedException
     */
    public static function validateUserAccess(User $user, Task $task): void
    {
        if ($task->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedException();
        }
    }

    /**
     * @throws TaskCompletedException
     */
    public static function validateTaskDelete(Task $task): void
    {
        if ($task->isCompleted()) {
            throw new TaskCompletedException();
        }
    }

    /**
     * @throws AllTaskMustBeCompletedException
     */
    public static function validateTaskComplete(Task $task): void
    {
        /** @var Task $child */
        foreach ($task->getChildren() as $child) {
            if (!$child->isCompleted()) {
                throw new AllTaskMustBeCompletedException();
            }
            self::validateTaskComplete($child);
        }
    }
}
