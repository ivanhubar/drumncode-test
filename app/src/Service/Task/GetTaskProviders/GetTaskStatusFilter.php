<?php

namespace App\Service\Task\GetTaskProviders;

use Doctrine\ORM\QueryBuilder;

class GetTaskStatusFilter implements GetTaskProviderInterface
{
    public const NAME = 'status';

    public function supports(string $filterName): bool
    {
        return self::NAME === $filterName;
    }

    public function provide(QueryBuilder $qb, string $value): QueryBuilder
    {
        return $qb->andWhere('t.status = :status')->setParameter('status', $value);
    }
}
