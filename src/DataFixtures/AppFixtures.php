<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Mobile;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(UserPasswordHasherInterface $userPasswordHasher) {
        $this->userPasswordHasher = $userPasswordHasher;
    }
    public function load(ObjectManager $manager): void
    {

        $userAdmin = new User();
        $userAdmin->setEmail("admin@bilemo.com");
        $userAdmin->setRoles(["ROLDE_ADMIN"]);
        $userAdmin->setPassword($this->userPasswordHasher->hashPassword($userAdmin, "password"));
        $manager->persist($userAdmin);
        
        for ($i = 0; $i < 20; $i++) {
            $mobile = new Mobile;
            $mobile->setTitle('Mobile ' . $i);
            $mobile->setFeature('Description technique du mobile ' . $i);
            $manager->persist($mobile);
        }

        for ($i = 0; $i < 10; $i++) {
            $customer = new Customer;
            $customer->setName('Utilisateur ' . $i);
            $manager->persist($customer);
        }

        $manager->flush();
    }
}
