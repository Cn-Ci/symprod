<?php

namespace App\Controller;

use App\Entity\PasswordEdit;
use App\Form\PasswordEditType;
use App\Form\RegisterEditType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * Permet de se connecter
     * 
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             header('location: index.php');
            //return $this->redirectToRoute('index_prod');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error]);

    }

    /**
     * Permet de se deconnecter
     * 
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * Permet de modifier son profil user
     * 
     * @Route("/registerEdit", name="register_edit")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function registerEdit(Request $request, EntityManagerInterface $manager) {
        $user = $this->getUser();

        $form = $this->createForm(RegisterEditType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Les modifications du profil ont bien été enregistrées !'
            );
        }

        return $this->render('registration/registerEdit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier le mot de passe user
     *
     * @Route("/passwordEdit", name="password_edit")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function passwordEdit(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder) {
        $passwordEdit = new PasswordEdit();
        $user = $this->getUser();
        $form = $this->createForm(PasswordEditType::class, $passwordEdit);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            if(!password_verify($passwordEdit->getOldPassword(), $user->getPassword())){
                $form->get('oldPassword')->addError(new FormError("Le mot de passe actuel est incorrect !"));
            }
            else
            {
                $newPassword = $passwordEdit->getNewPassword();
                $password = $encoder->encodePassword($user, $newPassword);
                $user->setPassword($password);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Le mot de passe du profil ont bien été modifié !'
                );
                return $this->redirectToRoute('index_prod');
            }
        }

        return $this->render('registration/passwordEdit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
