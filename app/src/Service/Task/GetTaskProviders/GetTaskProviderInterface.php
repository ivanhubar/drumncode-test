<?php

namespace App\Service\Task\GetTaskProviders;

use Doctrine\ORM\QueryBuilder;

interface GetTaskProviderInterface
{
    public function supports(string $filterName): bool;

    public function provide(QueryBuilder $qb, string $value): QueryBuilder;
}
