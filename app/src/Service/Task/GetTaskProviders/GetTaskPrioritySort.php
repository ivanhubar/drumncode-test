<?php

namespace App\Service\Task\GetTaskProviders;

use Doctrine\ORM\QueryBuilder;

class GetTaskPrioritySort implements GetTaskProviderInterface
{
    public const NAME = 'prioritySort';

    public function supports(string $filterName): bool
    {
        return self::NAME === $filterName;
    }

    public function provide(QueryBuilder $qb, string $value): QueryBuilder
    {
        return $qb->addOrderBy('t.priority', $value);
    }
}
