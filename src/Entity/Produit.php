<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ProduitRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 * @UniqueEntity(
 *  fields={"designation"},
 *  message="Ce produit existe déjà !"
 * )
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotNull(message="The designation must not to be null.")
     */
    private $designation;

    /**
     * @ORM\Column(type="float")
     * @Assert\Type(
     *     type="float",
     *     message="The value {{ value }} is not a valid {{ type }} ").
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(
     *      min = 2,
     *      max = 10,
     *      minMessage = "Your color must be at least {{ limit }} characters long",
     *      maxMessage = "Your color cannot be longer than {{ limit }} characters"
     * )
     */
    private $couleur;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="produit")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorieProduit;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="client")
     */
    private $Users;

    /**
     * @ORM\ManyToMany(targetEntity=Client::class, mappedBy="produit")
     */
    private $clients;

    public function __construct()
    {
        $this->Users = new ArrayCollection();
        $this->clients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getCategorieProduit(): ?Categorie
    {
        return $this->categorieProduit;
    }

    public function setCategorieProduit(?Categorie $categorieProduit): self
    {
        $this->categorieProduit = $categorieProduit;

        return $this;
    }


    public function __toString() {
        if(is_null($this->categorieProduit)) {
            return 'NULL';
        }
        return $this->categorieProduit;
    }


    /**
     * @return Collection|Client[]
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients[] = $client;
            $client->addProduit($this);
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        if ($this->clients->removeElement($client)) {
            $client->removeProduit($this);
        }

        return $this;
    }
}
