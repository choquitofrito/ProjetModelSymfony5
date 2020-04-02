<?php

namespace App\DataFixtures;

use App\Entity\Auteur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class AuteurFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10 ; $i++){
            $auteur = new Auteur();
            $auteur->setNom ("auteur".$i);
            $auteur->setNationalite ("pays".$i);
            $manager->persist ($auteur);
        }
 
        $manager->flush();
    }
}
