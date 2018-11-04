<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RechercheRepository")
 */
class Recherche
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $sexe;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $age;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $fumeur;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $musique_favoris;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $club_favoris;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $recherchant_id;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $resultatRecherche = [];

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $statut;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getFumeur(): ?bool
    {
        return $this->fumeur;
    }

    public function setFumeur(?bool $fumeur): self
    {
        $this->fumeur = $fumeur;

        return $this;
    }

    public function getMusiqueFavoris(): ?string
    {
        return $this->musique_favoris;
    }

    public function setMusiqueFavoris(?string $musique_favoris): self
    {
        $this->musique_favoris = $musique_favoris;

        return $this;
    }

    public function getClubFavoris(): ?string
    {
        return $this->club_favoris;
    }

    public function setClubFavoris(?string $club_favoris): self
    {
        $this->club_favoris = $club_favoris;

        return $this;
    }

    public function getRecherchantId(): ?User
    {
        return $this->recherchant_id;
    }

    public function setRecherchantId(?User $recherchant_id): self
    {
        $this->recherchant_id = $recherchant_id;

        return $this;
    }

    public function getResultatRecherche(): ?array
    {
        return $this->resultatRecherche;
    }

    public function setResultatRecherche(?array $resultatRecherche): self
    {
        $this->resultatRecherche = $resultatRecherche;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }


}
