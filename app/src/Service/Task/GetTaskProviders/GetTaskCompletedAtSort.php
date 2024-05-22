<?php

namespace App\Service\Task\GetTaskProviders;

use Doctrine\ORM\QueryBuilder;

class GetTaskCompletedAtSort implements GetTaskProviderInterface
{
    public const NAME = 'completedAtSort';

    public function supports(string $filterName): bool
    {
        return self::NAME === $filterName;
    }

    public function provide(QueryBuilder $qb, string $value): QueryBuilder
    {
        return $qb->addOrderBy('t.completedAt', $value);
    }
}
