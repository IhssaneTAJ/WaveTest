<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;


class Contact{
    
    /**
     * @var string|null
     * @Assert\Length(min=4, max=100)
     */
    private $nom;


    /**
    * @var string|null
    * @Assert\Regex(pattern="/[0-9]{10}/")
    */
    private $telephone;


    /**
    * @var string|null
    * @Assert\Email()
    */
    private $email;


    /**
    * @var string|null
    * @Assert\Length(min=15)
    */
    private $message;

    /**
     * @var Produit|null
     */
    private $produit;

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getProduit(): ?string
    {
        return $this->produit;
    }

    public function setProduit(Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }
}