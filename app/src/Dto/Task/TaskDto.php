<?php

namespace App\Dto\Task;

use App\Enum\Task\TaskStatus;

readonly class TaskDto
{
    /**
     * @param TaskDto[] $children
     */
    public function __construct(
        public int $id,
        public TaskStatus $status,
        public int $priority,
        public string $title,
        public string $description,
        public \DateTime $createdAt,
        public ?\DateTime $completedAt,
        public array $children = [],
    ) {
    }
}
