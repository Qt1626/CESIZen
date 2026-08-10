<?php

namespace App\Controller;

use App\Entity\InformationPage;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AcceuilController extends AbstractController{
    #[Route('/', name: 'app_acceuil')]
    public function index(EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {


        // Récupération du repository de l'entité InformationPage
        $repository = $entityManager->getRepository(InformationPage::class);

        // Récupération de toutes les pages d'information ayant le statut "visible" et trié par "ordreAffichage" croissant
        $informationPageArray = $repository->findBy(["estVisible"=>"true"],["ordreAffichage"=>"ASC"]);

        // Logger les titres des pages d'information
        foreach ($informationPageArray as $informationPage) {
        //    $logger->info($informationPage->getTitrePage());
        }

        // Passer le tableau à notre fichier twig
        return $this->render('home/home.html.twig', [
      'information_page_array' => $informationPageArray,]);

    }
}


