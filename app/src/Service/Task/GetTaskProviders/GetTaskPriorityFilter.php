<?php

namespace App\Service\Task\GetTaskProviders;

use Doctrine\ORM\QueryBuilder;

class GetTaskPriorityFilter implements GetTaskProviderInterface
{
    public const NAME = 'priority';

    public function supports(string $filterName): bool
    {
        return self::NAME === $filterName;
    }

    public function provide(QueryBuilder $qb, string $value): QueryBuilder
    {
        return $qb->andWhere('t.priority = :priority')->setParameter('priority', (int) $value);
    }
}
