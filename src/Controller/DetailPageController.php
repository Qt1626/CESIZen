<?php

namespace App\Controller;


use App\Entity\InformationPage;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DetailPageController extends AbstractController
{
    private $detailPageRepository;

    #[Route('/detail-page/{id}', name: 'app_detail_page')]
    public function index(int $id,EntityManagerInterface $entityManager,LoggerInterface $logger): Response
    {
        // Récupération du repository de l'entité InformationPage
        $repository = $entityManager->getRepository(InformationPage::class);

        // Récupération de la page passée en paramètre
        $detailPage = $repository->findOneBy(['id' => $id]);

        $toto = sprintf("id = %d", $id);
        $logger->info($toto);
        $logger->info($detailPage->getTitrePage());
        if (!$detailPage) {
            throw $this->createNotFoundException('La page de detail n\'a pas été trouvée.');
        }

        return $this->render('detail_page/detail_page.html.twig', [
            'detail_page' => $detailPage,
        ]);
    }
}