<?php

namespace App\DTO;

use App\Entity\Category;
use Symfony\Component\Validator\Constraints as Assert;

class CategoryDTO
{
    /**
     * @Assert\NotBlank(message="This value should not be blank.", payload=null)
     */
    private $category;

    /**
     * Fill entity with data from the DTO.
     *
     * @param Category $category
     * @return Category
     */
    public function fill(Category $category): Category
    {
        $category->setCategory($this->category);

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
        $this->category = $category->getCategory();

        return $this;
    }

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

}
