<?php

namespace App\Service\Task\GetTaskProviders;

use Doctrine\ORM\QueryBuilder;

class GetTaskTitleFilter implements GetTaskProviderInterface
{
    public const NAME = 'title';

    public function supports(string $filterName): bool
    {
        return self::NAME === $filterName;
    }

    public function provide(QueryBuilder $qb, string $value): QueryBuilder
    {
        return $qb->andWhere('ts_match(to_tsvector(t.title), plainto_tsquery(:title)) = true')
            ->setParameter('title', $value);
    }
}
