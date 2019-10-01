<?php

namespace App\DTO;

use App\Entity\Author;
use App\Entity\Category;
use App\Entity\Post;
use Symfony\Component\Validator\Constraints as Assert;

class PostDTO
{
    /**
     * @Assert\NotBlank()
     * @var string|null
     */
    private $title;

    /**
     * @Assert\NotBlank()
     * @var string|null
     */
    private $text;

    /**
     * @Assert\Valid()
     * @var Category|null
     */
    private $category;

    /**
     * @Assert\Valid()
     * @var AuthorDTO|null
     */
    private $author;

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

    /**
     * @return Author|null
     */
    public function getAuthor(): ?Author
    {
        if ($this->author !== null) {
            return $this->author->fill(new Author());
        }

        return null;
    }

    /**
     * @param AuthorDTO|null $authorDTO
     */
    public function setAuthor(?AuthorDTO $authorDTO): void
    {
        $this->author = $authorDTO;
    }

    /**
     * @param Post $post
     * @return Post
     */
    public function fill(Post $post): Post
    {
        $post->setTitle($this->title);
        $post->setText($this->text);
        $post->setCategory($this->getCategory());
        $post->setAuthor($this->getAuthor());

        return $post;
    }

    /**
     * @param Post $post
     * @return $this
     */
    public function extract(Post $post): self
    {
        $this->setTitle($post->getTitle());
        $this->setText($post->getText());
        $this->setCategory($post->getCategory());

        $authorDTO = new AuthorDTO();
        $authorDTO->extract($post->getAuthor());
        $this->setAuthor($authorDTO);

        return $this;
    }

}
