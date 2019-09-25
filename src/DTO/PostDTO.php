<?php

namespace App\DTO;

use App\Entity\Category;
use App\Entity\Post;
use Symfony\Component\Validator\Constraints as Assert;

class PostDTO
{
    /**
     * @Assert\NotBlank(message="This value should not be blank.", payload=null)
     */
    private $title;

    /**
     * @Assert\NotBlank(message="This value should not be blank.", payload=null)
     */
    private $text;

    /**
     * @Assert\NotBlank(message="This value should not be blank.", payload=null)
     * @var Category
     */
    private $category;

    /**
     * Create DTO, optionally extracting data from a model.
     *
     * @param Post|null $post
     */
    public function __construct(?Post $post = null)
    {
        if ($post instanceof Post) {
            $this->extract($post);
        }
    }

    /**
     * Fill entity with data from the DTO.
     *
     * @param Post $post
     * @return Post
     */
    public function fill(Post $post): Post
    {
        $post->setTitle($this->title);
        $post->setText($this->text);
        $post->setCategory($this->getCategory());

        return $post;
    }

    /**
     * Extract data from entity into the DTO.
     *
     * @param Post $post
     * @return $this
     */
    public function extract(Post $post): self
    {
        $this->title = $post->getTitle();
        $this->text = $post->getText();
        $this->category = $post->getCategory();

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string|null $text
     */
    public function setText(?string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     */
    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }
}
