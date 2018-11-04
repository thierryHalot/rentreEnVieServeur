<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HeaderMsgRepository")
 */
class HeaderMsg
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
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_del;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $emetteur_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $recepteur_id;

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

    public function getIsDel(): ?bool
    {
        return $this->is_del;
    }

    public function setIsDel(?bool $is_del): self
    {
        $this->is_del = $is_del;

        return $this;
    }

    public function getEmetteurId(): ?User
    {
        return $this->emetteur_id;
    }

    public function setEmetteurId(?User $emetteur_id): self
    {
        $this->emetteur_id = $emetteur_id;

        return $this;
    }

    public function getRecepteurId(): ?User
    {
        return $this->recepteur_id;
    }

    public function setRecepteurId(?User $recepteur_id): self
    {
        $this->recepteur_id = $recepteur_id;

        return $this;
    }
}
