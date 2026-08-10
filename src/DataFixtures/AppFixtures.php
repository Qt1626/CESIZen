<?php

namespace App\DataFixtures;

use App\Controller\AdminLogController;
use App\Controller\ExerciceRespirationController;
use App\Controller\InformationPageController;
use App\Controller\InfoUtilisateurController;
use App\Controller\UtilisateurController;
use App\Factory\AdminLogFactory;
use App\Factory\ExerciceRespirationFactory;
use App\Factory\InformationPageFactory;
use App\Factory\InfoUtilisateurFactory;
use App\Factory\UtilisateurFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        AdminLogFactory::createMany(100);
        ExerciceRespirationFactory::createMany(10);
        InformationPageFactory::createMany(100);
        InfoUtilisateurFactory::createMany(100);
        UtilisateurFactory::createMany(100);
        $manager->flush();
    }
}
