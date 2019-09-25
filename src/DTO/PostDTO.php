<?php

namespace App\DTO;

use App\Entity\Post;
use Symfony\Component\Validator\Constraints as Assert;

class PostDTO
{
    /**
     * @Assert\NotBlank(message="This value should not be blank.", payload=null)
     */
    public $title;

    /**
     * @Assert\NotBlank(message="This value should not be blank.", payload=null)
     */
    public $text;

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

        return $this;
    }
}
