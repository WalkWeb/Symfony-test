<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="fullname", type="string", length=255)
     */
    private $fullname;

    /**
     * @ORM\OneToMany(targetEntity="Exp", mappedBy="user", cascade={"persist"})
     */
    private $exp;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->exp = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    /**
     * @param string $fullname
     */
    public function setFullname(string $fullname): void
    {
        $this->fullname = $fullname;
    }

    /**
     * Add exp
     *
     * @param Exp $exp
     *
     * @return User
     */
    public function addExp(Exp $exp)
    {
        $this->exp[] = $exp;
        // setting the current user to the $exp,
        // adapt this to whatever you are trying to achieve
        $exp->setUser($this);
        return $this;
    }

    /**
     * Remove exp
     *
     * @param Exp $exp
     */
    public function removeExp(Exp $exp)
    {
        $this->exp->removeElement($exp);
    }

    /**
     * Get exp
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExp()
    {
        return $this->exp;
    }

    public function __toString(): ?string
    {
        return $this->fullname;
    }
}
