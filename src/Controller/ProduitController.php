<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Produit;
use App\Entity\Categorie;
use App\Form\ProduitType;
use App\Service\ProduitService;
use App\Repository\UserRepository;
use App\Repository\ProduitRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Exceptions\ProduitException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

   
class ProduitController extends AbstractController
{

    function __construct(ProduitRepository $repo, EntityManagerInterface $manager)
    {
        $this->produitRepository = $repo;
        $this->produitManager = $manager;
    }

     /**
    * @Route("/home", name="index_prod")
    */
    public function index(): Response
    {  
        return $this->render('index.html.twig', [
        ]);
    }

    
    /**
    * @Route("/produit/afficher", name="list_produit")
    */
    public function afficheProduits(): Response
    {
        
        //$repo = $this->getDoctrine()->getRepository(Produit::class);
        //$produits = $repo->findAll();
        $produit = $this->produitRepository->findAll();  
        
        return $this->render('produit/list.html.twig', [
            'produits' => $produit,
        ]);
    }

    /**
    * @Route("/produit/add", name="add_produit")
    *
    * @return Response
    */
    public function add(Request $request): Response
    {
    
            $produit = new Produit();
            $form = $this->createForm(ProduitType::class, $produit);
            // $form = $this->createFormBuilder($produit)->add("designation")
            //  ->add("prix")
            //  ->add("couleur")
            //  ->add("save", SubmitType::Class, [ "label" => "Ajouter le produit"])
            //  ->getForm();
            
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
            //     // $produit = $form->getData();
            //    // $manager = $this->getDoctrine()->getManager();
            //     $manager->persist($produit);
            //     $manager->flush();
                $this->produitManager->persist($produit);
                $this->produitManager->flush();
                $this->addFlash( 
                    'success',
                    "L'annonce {$produit->getDesignation()} a bien été ajouté !"
                );
                return $this->redirectToRoute("list_produit");
            }
       
            return $this->render('produit/add.html.twig', [
                'title' => 'Ajout',
                'titreForm' => "Ajouter une nouvelle annonce ",
                'form' => $form->createView(),
                'btnTitle' => 'Ajouter un produit',
            ]);
    } 

    /**
    * @Route("/produit/edit/{id}", name="edit_produit", methods={"GET","POST"})
    * 
    * @return Response
    */
   public function editProduit(Produit $produit, Request $request): Response
   {
             $form = $this->createForm(ProduitType::class, $produit);
            
             $form->handleRequest($request);
             if($form->isSubmitted() && $form->isValid()){
                //     // $produit = $form->getData();
                //    // $manager = $this->getDoctrine()->getManager();
                //     $manager->persist($produit);
                //     $manager->flush();
                $this->produitManager->persist($produit);
                $this->produitManager->flush();
                    $this->addFlash( 
                        'success',
                        "L'annonce {$produit->getDesignation()} a bien été modifier !"
                    );
                    return $this->redirectToRoute("list_produit");
                }
        
            return $this->render('produit/edit.html.twig', [
                'title' => 'Modification',
                'titreForm' => "Modifier l'annonce {$produit->getDesignation()}",
                'produit' => $produit,
                'form' => $form->createView(),
                'btnTitle' => 'Modifier le produit',
            ]);
   } 

    /**
     * @Route("/produit/delete/{id}", name="delete_produit")
     * 
     * @return Response
     */
    public function delete(Produit $produit): Response
    {
            // $manager->remove($produit);
            // $manager->flush();
            $this->produitManager->remove($produit);
            $this->produitManager->flush();
            $this->addFlash( 
                'success',
                "L'annonce {$produit->getDesignation()} a bien été supprimé !"
            );
            return $this->redirectToRoute('list_produit');
        return $this->render('produit/list.html.twig', [
        ]);
    }
    
     /** Permet d'afficher une seule annonce
     * 
     * @Route("/produit/show/{id}", name ="show_produit")
     * 
     * 
     * @return Response
     */
    public function showOne(Produit $produit) {

            // recupere l'annonce qui correspond au produit
            // $produit = $repo->findOneById($id);

            return $this->render('produit/show.html.twig', [ 
                'title' => 'Consultation',
                'produit' => $produit,
            ]);
        
    }

}
