<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Produit;
use App\Service\UserService;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class UserController extends AbstractController
{
    /**
     * @Route("/user/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet d'afficher les infos de l'user
     * 
     * @Route("/show/{slug}", name ="user_show", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function show(User $user) {
        // recupere l'annonce qui correspond au slug
        //$produitClient = $user->findOneByClient();
        return $this->render('user/index.html.twig', [ 
           'user' => $user,
        ]);
    }

    
}
