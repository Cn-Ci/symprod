<?php 

namespace App\Service;


use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Exceptions\ProduitException;
use Doctrine\DBAL\Exception\DriverException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitService extends AbstractController {

    private $produitRepository; 
    private $produitManager;

    function __construct(ProduitRepository $repo, EntityManagerInterface $manager)
    {
        $this->produitRepository = $repo;
        $this->produitManager = $manager;
    }

    function searchAll() 
    {
        try {
            $produit = $this->produitRepository->findAll();    
        } catch (\Exception $e) {
            throw new ProduitException ("un pb est survenu", $e);
        } 
        return $produit; 
    }

    function add(Produit $produit) 
    {
        try {
        $this->produitManager->persist($produit);
        $this->produitManager->flush();
        } catch (\Exception $e) {
            throw new ProduitException ("un pb est survenu add", $e);
        }
    }

    function update(Produit $produit)
    {
        try {
            $this->produitManager->persist($produit);
            $this->produitManager->flush();
        } catch (\Exception $e) {
            throw new ProduitException ("un pb est survenu update", $e);
        }    
    }

    function delete(Produit $id) 
    {
        try {
            $this->produitManager->remove($id);
            $this->produitManager->flush();
        } catch (\Exception $e) {
            throw new ProduitException ("un pb est survenu delete", $e);
        }
    }

}