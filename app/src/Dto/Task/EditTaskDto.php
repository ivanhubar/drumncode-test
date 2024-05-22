<?php

namespace App\Dto\Task;

readonly class EditTaskDto
{
    public function __construct(
        public string $title,
        public string $description,
        public int $priority,
    ) {
    }
}
