<?php

namespace App\Manager;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use Doctrine\ORM\QueryBuilder;

class TaskManager
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
    ) {
    }

    public function buildQueryBuilderForListing(User $user): QueryBuilder
    {
        return $this->taskRepository->buildQueryBuilderForListing($user);
    }

    public function create(Task $task): void
    {
        $this->taskRepository->add($task, true);
    }

    public function update(Task $task): void
    {
        $this->taskRepository->update();
    }

    public function delete(Task $task): void
    {
        $this->taskRepository->delete($task);
    }

    public function findById(int $id): ?Task
    {
        return $this->taskRepository->find($id);
    }
}
