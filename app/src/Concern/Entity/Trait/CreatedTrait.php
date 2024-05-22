<?php

namespace App\Concern\Entity\Trait;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\PrePersist;

/**
 * @note This trait needs #[ORM\HasLifecycleCallbacks] in entity
 */
trait CreatedTrait
{
    #[Column(name: 'created_at', type: 'datetime', nullable: false)]
    private \DateTime $createdAt;

    #[PrePersist]
    public function created(): void
    {
        $this->createdAt = new \DateTime();
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
