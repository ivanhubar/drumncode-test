<?php

namespace App\Dto\Task;

readonly class CreateTaskDto
{
    public function __construct(
        public int $priority,
        public string $title,
        public string $description,
        public ?int $parentId,
    ) {
    }
}
