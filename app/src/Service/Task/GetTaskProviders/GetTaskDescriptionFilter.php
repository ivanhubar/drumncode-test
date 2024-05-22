<?php

namespace App\Service\Task\GetTaskProviders;

use Doctrine\ORM\QueryBuilder;

class GetTaskDescriptionFilter implements GetTaskProviderInterface
{
    public const NAME = 'description';

    public function supports(string $filterName): bool
    {
        return self::NAME === $filterName;
    }

    public function provide(QueryBuilder $qb, string $value): QueryBuilder
    {
        return $qb->andWhere('ts_match(to_tsvector(t.description), plainto_tsquery(:description)) = true')
            ->setParameter('description', $value);
    }
}
