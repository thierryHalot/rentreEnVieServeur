<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $age;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $sexe;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tel;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $mdp;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $fumeur;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $club_favoris;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $musique_favoris;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    private $img;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $mode_sortie;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $perimetre;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $longitude;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeUser")
     */
    private $type_user_id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_del;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

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

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(?int $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(?string $mdp): self
    {
        $this->mdp = $mdp;

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

    public function getClubFavoris(): ?string
    {
        return $this->club_favoris;
    }

    public function setClubFavoris(?string $club_favoris): self
    {
        $this->club_favoris = $club_favoris;

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

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getModeSortie(): ?bool
    {
        return $this->mode_sortie;
    }

    public function setModeSortie(?bool $mode_sortie): self
    {
        $this->mode_sortie = $mode_sortie;

        return $this;
    }

    public function getPerimetre(): ?int
    {
        return $this->perimetre;
    }

    public function setPerimetre(?int $perimetre): self
    {
        $this->perimetre = $perimetre;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getTypeUserId(): ?TypeUser
    {
        return $this->type_user_id;
    }

    public function setTypeUserId(?TypeUser $type_user_id): self
    {
        $this->type_user_id = $type_user_id;

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

    public function getJson(){

        $user = array(

          "id" => $this->id,

          "nom"=> $this->nom,

          "prenom"=> $this->prenom,

          "age"=>$this->age,

          "sexe"=>$this->sexe,

          "tel"=>$this->tel,

          "adresse"=>$this->adresse,

          "mail"=>$this->mail,

          "pseudo"=>$this->pseudo,

          "mdp"=>$this->mdp,

          "fumeur"=>$this->fumeur,

          "clubFavoris"=>$this->club_favoris,

          "musiqueFavoris"=>$this->musique_favoris,

          "modeSortie"=>$this->mode_sortie,

          "perimetre"=>$this->perimetre,

          "latitude" => $this->latitude,

          "longitude"=> $this->longitude,

          "isDel" => $this->is_del

        );

        if($this->getTypeUserId() !== null){

            $user["type"] = $this->getTypeUserId()->getRole();

        }else{

            $user["type"] = null ;

        }

        return json_encode($user);

    }
}
