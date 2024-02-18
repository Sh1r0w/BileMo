<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Mobile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
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
