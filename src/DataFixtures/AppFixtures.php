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
    /**
     * The function loads initial data into the database including users, mobiles, and customers.
     * 
     * @param ObjectManager manager The `manager` parameter in the `load` function is an instance of
     * `ObjectManager` which is used in Doctrine to manage entities and perform operations like
     * persisting and flushing entities to the database. In this context, the `manager` is being used
     * to persist entities like `User`, `Mobile
     */
    public function load(ObjectManager $manager): void
    {
        
        $userAdmin = new User();
        $userAdmin->setEmail("admin@bilemo.com");
        $userAdmin->setRoles(["ROLE_ADMIN"]);
        $userAdmin->setPassword($this->userPasswordHasher->hashPassword($userAdmin, "password"));
        $manager->persist($userAdmin);

        $this->setReference('user-1', $userAdmin);


        $user = new User();
        $user->setEmail("user@bilemo.com");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "password"));
        $manager->persist($user);

        $this->setReference('user-2', $user);

        
        for ($i = 0; $i < 20; $i++) {
            $mobile = new Mobile;
            $mobile->setTitle('Mobile ' . $i);
            $mobile->setFeature('Description technique du mobile ' . $i);
            $manager->persist($mobile);
        }

        for ($i = 0; $i < 10; $i++) {
            $customer = new Customer;
            $customer->setName('Utilisateur ' . $i);
            $customer->setUser($this->getReference('user-'. rand(1,2)));
            $manager->persist($customer);
        }

        $manager->flush();
    }
}
