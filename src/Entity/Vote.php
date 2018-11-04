<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VoteRepository")
 */
class Vote
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbr_vote;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $votant_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $voter_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getNbrVote(): ?int
    {
        return $this->nbr_vote;
    }

    public function setNbrVote(?int $nbr_vote): self
    {
        $this->nbr_vote = $nbr_vote;

        return $this;
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

    public function getVotantId(): ?User
    {
        return $this->votant_id;
    }

    public function setVotantId(?User $votant_id): self
    {
        $this->votant_id = $votant_id;

        return $this;
    }

    public function getVoterId(): ?User
    {
        return $this->voter_id;
    }

    public function setVoterId(?User $voter_id): self
    {
        $this->voter_id = $voter_id;

        return $this;
    }
}
