<?php

namespace App\DTO;

use App\Entity\Category;
use Symfony\Component\Validator\Constraints as Assert;

class CategoryDTO
{
    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     * @Assert\Positive()
     */
    private $id;

    /**
     * @Assert\NotBlank()
     */
    private $category;

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * Fill entity with data from the DTO.
     *
     * @param Category $category
     * @return Category
     */
    public function fill(Category $category): Category
    {
        $category->setCategory($this->getCategory());
        $category->setId($this->getId());

        return $category;
    }

    /**
     * Extract data from entity into the DTO.
     *
     * @param Category $category
     * @return $this
     */
    public function extract(Category $category): self
    {
        $this->setCategory($category->getCategory());
        $this->setId($category->getId());

        return $this;
    }

}
