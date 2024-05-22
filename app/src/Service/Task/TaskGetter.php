<?php

namespace App\Service\Task;

use App\Dto\Task\TaskDto;
use App\DtoBuilder\Task\TaskDtoBuilder;
use App\Entity\User;
use App\Manager\TaskManager;
use App\Service\Task\GetTaskProviders\GetTaskProviderInterface;

class TaskGetter
{
    /**
     * @param GetTaskProviderInterface[] $providers
     */
    public function __construct(
        private readonly TaskManager $taskManager,
        private readonly iterable $providers,
    ) {
    }

    /**
     * @return TaskDto[]
     */
    public function get(User $user, array $filters): array
    {
        $this->processSorting($filters);

        return $this->getTasks($user, $filters);
    }

    private function getTasks(User $user, array &$filters): array
    {
        $qb = $this->taskManager->buildQueryBuilderForListing($user);

        foreach ($this->providers as $provider) {
            foreach ($filters as $filterName => $filterValue) {
                if ($provider->supports($filterName)) {
                    $qb = $provider->provide($qb, (string) $filterValue);
                }
            }
        }

        $result = [];

        foreach ($qb->getQuery()->toIterable() as $task) {
            $result[] = TaskDtoBuilder::fromEntity($task);
        }

        return array_values($result);
    }

    private function processSorting(array &$filters): void
    {
        if (array_key_exists('sortBy', $filters)) {
            $sortBy = $filters['sortBy'];
            unset($filters['sortBy']);
            foreach (explode(',', $sortBy) as $sortPair) {
                list($field, $direction) = explode(' ', trim($sortPair));
                $filters[$field.'Sort'] = strtolower($direction);
            }
        }
    }
}
