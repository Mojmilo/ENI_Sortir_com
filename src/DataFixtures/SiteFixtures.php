<?php

namespace App\DataFixtures;

use App\Entity\Site;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SiteFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $site = new Site();
        $site->setName('SAINT HERBLAIN');
        $manager->persist($site);

        $site2 = new Site();
        $site2->setName('CHARTES DE BRETAGNE');
        $manager->persist($site2);

        $site3 = new Site();
        $site3->setName('LA ROCHE SUR YON');
        $manager->persist($site3);

        $manager->flush();
    }
}
