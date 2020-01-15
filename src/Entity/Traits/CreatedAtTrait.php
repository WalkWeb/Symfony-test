<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use DateTimeInterface;

trait CreatedAtTrait
{
    /**
     * @ORM\Column(type="datetime")
     * @var DateTimeInterface|null
     */
    private $createdAt;

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist(): void
    {
        $this->createdAt = new DateTime();
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     * @return $this
     */
    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
