<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use DateTimeInterface;

trait UpdatedAtTrait
{
    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTimeInterface|null
     */
    private $updatedAt;

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate(): void
    {
        $this->updatedAt = new DateTime();
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeInterface|null $updatedAt
     */
    public function setUpdatedAt(?DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
