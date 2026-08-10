<?php

namespace App\Controller;


use App\Entity\ExerciceRespiration;
use App\Entity\InformationPage;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DetailExerciceRespirationController extends AbstractController
{
    private $detailExerciceRespirationRepository;

    #[Route('/detail-exercice-respiration/{id}', name: 'app_detail_exercice_respiration')]
    public function index(int $id,EntityManagerInterface $entityManager,LoggerInterface $logger): Response
    {
        // Récupération du repository de l'entité ExerciceRespiration
        $repository = $entityManager->getRepository(ExerciceRespiration::class);

        // Récupération de l'eexercice passé en paramètre
        $detailExerciceRespiration = $repository->findOneBy(['id' => $id]);

        $toto = sprintf("id = %d", $id);
        $logger->info($toto);
        $logger->info($detailExerciceRespiration->getNomComplet());
        if (!$detailExerciceRespiration) {
            throw $this->createNotFoundException('La page d\'exercice n\'a pas été trouvée.');
        }

        return $this->render('detail_exercice_respiration/detail_exercice_respiration.html.twig', [
            'detail_exercice_respiration' => $detailExerciceRespiration,
        ]);
    }
}