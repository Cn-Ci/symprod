<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);
        $date = $faker->dateTimeBetween();
                    $inscription = $faker->dateTimeBetween($startDate = '-30 years', $endDate = 'now', $timezone = null);

        $adminUser = new User();
        $adminUser->setEmail('ci@symfony.com')
                ->setPassword($this->encoder->encodePassword($adminUser, 'password'))
                ->setNom('Cn')
                ->setPrenom('Cindy')
                ->setDateAnniversaire($date)
                ->setDateInscription($inscription)
                ->setPicture('https://www.hdnicewallpapers.com/Walls/Big/Cat/Beautiful_Cat_Imge_Download.jpg')
                ->setIntroduction($faker->sentence(2))
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                ->addUserRole($adminRole);

        $manager->persist($adminUser);
        $manager->flush();
    }

    
}
