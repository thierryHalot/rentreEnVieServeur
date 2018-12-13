<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MsgRepository")
 */
class Msg
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80, nullable=true)
     */
    private $objet;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $contenu;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\HeaderMsg")
     */
    private $msg_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(?string $objet): self
    {
        $this->objet = $objet;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): self
    {
        $this->contenu = $contenu;

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

    public function getMsgId(): ?HeaderMsg
    {
        return $this->msg_id;
    }

    public function setMsgId(?HeaderMsg $msg_id): self
    {
        $this->msg_id = $msg_id;

        return $this;
    }

    public function getTabAssoMsg(){

        $msg = array(

            "id" => $this->id,
            "objet" => $this->objet,
            "contenu"=>$this->contenu,
            "date"=> $this->date->format('Y-m-d H:i')
        );
        if($this->getMsgId() !== null){

            $msg["idEmetteur"] = $this->getMsgId()->getEmetteurId()->getId();
            $msg["idRecepteur"] = $this->getMsgId()->getRecepteurId()->getId();

        }else{

            $msg["idEmetteur"] = null ;
            $msg["idRecepteur"] = null;
        }
        return $msg;

    }
}
