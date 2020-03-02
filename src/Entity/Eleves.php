<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ElevesRepository")
 */
class Eleves implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\College", inversedBy="eleves")
     * @ORM\JoinColumn(nullable=false)
     */
    private $college;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $classe;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $niveau;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=0)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $role ;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $avatar;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCollege(): ?college
    {
        return $this->college;
    }

    public function setCollege(?college $college): self
    {
        $this->college = $college;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getClasse(): ?string
    {
        return $this->classe;
    }

    public function setClasse(string $classe): self
    {
        $this->classe = $classe;

        return $this;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role): void
    {
        $this->role = $role;
    }

    public function setNiveau(?string $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    /**
     * @inheritDoc
     */
    public function serialize()
    {
        return serialize(
            [
                $this->id,
                $this->nom,
                $this->prenom,
                $this->email,
                $this->password,
                $this->pseudo,
                $this->college,
                $this->classe,
                $this->niveau
            ]);
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->nom,
            $this->prenom,
            $this->email,
            $this->password,
            $this->pseudo,
            $this->college,
            $this->classe,
            $this->niveau
            ) = unserialize($serialized);
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return [$this->role];
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }
}
