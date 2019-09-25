<?php

namespace App\DTO;

use App\Entity\Author;
use App\Entity\Country;
use Symfony\Component\Validator\Constraints as Assert;

class AuthorDTO
{
    /**
     * @Assert\NotBlank(message="This value should not be blank.", payload=null)
     * @var string|null
     */
    private $name;

    /**
     * @Assert\NotBlank(message="This value should not be blank.", payload=null)
     * @var Country|null
     */
    private $country;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Country|null
     */
    public function getCountry(): ?Country
    {
        return $this->country;
    }

    /**
     * @param Country|null $country
     */
    public function setCountry(?Country $country): void
    {
        $this->country = $country;
    }

    /**
     * Fill entity with data from the DTO.
     *
     * @param Author $author
     * @return Author
     */
    public function fill(Author $author): Author
    {
        $author->setName($this->name);
        $author->setCountry($this->country);

        return $author;
    }

    /**
     * Extract data from entity into the DTO.
     *
     * @param Author $author
     * @return $this
     */
    public function extract(Author $author): self
    {
        $this->name = $author->getName();
        $this->country = $author->getCountry();

        return $this;
    }
}
