<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationFormType extends ApplicationType
{

    

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $dateInscription = new User();
        $dateInscription->setDateInscription(new \DateTime('now'));

        $builder
            ->add('email', EmailType::class, $this->getConfiguration ("Email", "Votre email.."))
            ->add('nom', TextType::class, $this->getConfiguration ("Nom", "Votre nom de famille.."))
            ->add('prenom', TextType::class, $this->getConfiguration ("Prenom", "Votre prénom.."))
            ->add('dateAnniversaire', BirthdayType::class, $this->getConfiguration ("Date d'anniversaire", "Votre date d'anniversaire.."))
            ->add('password', PasswordType::class, $this->getConfiguration("Confirmation de mot de passe", "Veuillez confirmer votre mot de passe"))
            ->add('passwordConfirm', PasswordType::class, $this->getConfiguration("Confirmation de mot de passe", "Veuillez confirmer votre mot de passe"))
            ->add('picture', UrlType::class, $this->getConfiguration ("Photo de profil", "Url de votre avatar.."))
            ->add('introduction', TextType::class, $this->getConfiguration ("Introduction", "Présentez vous en quelques mots.."))
            ->add('description', TextareaType::class, $this->getConfiguration ("Description détaillée", "C'est le moment de vous décrire en detail.."))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
