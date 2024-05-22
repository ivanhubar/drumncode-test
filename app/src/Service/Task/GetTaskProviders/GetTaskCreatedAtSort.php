<?php

namespace App\Service\Task\GetTaskProviders;

use Doctrine\ORM\QueryBuilder;

class GetTaskCreatedAtSort implements GetTaskProviderInterface
{
    public const NAME = 'createdAtSort';

    public function supports(string $filterName): bool
    {
        return self::NAME === $filterName;
    }

    public function provide(QueryBuilder $qb, string $value): QueryBuilder
    {
        return $qb->addOrderBy('t.createdAt', $value);
    }
}
