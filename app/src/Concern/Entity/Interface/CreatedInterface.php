<?php

namespace App\Concern\Entity\Interface;

interface CreatedInterface
{
    public function created(): void;

    public function getCreatedAt(): \DateTime;
}
