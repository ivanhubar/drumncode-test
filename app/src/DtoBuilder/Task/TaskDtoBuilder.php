<?php

namespace App\DtoBuilder\Task;

use App\Dto\Task\TaskDto;
use App\Entity\Task;

readonly class TaskDtoBuilder
{
    public static function fromEntity(Task $task): TaskDto
    {
        $children = [];
        foreach ($task->getChildren() as $child) {
            $children[] = self::fromEntity($child);
        }

        return new TaskDto(
            $task->getId(),
            $task->getStatus(),
            $task->getPriority(),
            $task->getTitle(),
            $task->getDescription(),
            $task->getCreatedAt(),
            $task->getCompletedAt(),
            $children
        );
    }
}
