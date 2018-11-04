<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BlackListRepository")
 */
class BlackList
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $bloquand_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $bloquer_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getBloquandId(): ?User
    {
        return $this->bloquand_id;
    }

    public function setBloquandId(?User $bloquand_id): self
    {
        $this->bloquand_id = $bloquand_id;

        return $this;
    }

    public function getBloquerId(): ?User
    {
        return $this->bloquer_id;
    }

    public function setBloquerId(?User $bloquer_id): self
    {
        $this->bloquer_id = $bloquer_id;

        return $this;
    }
}
