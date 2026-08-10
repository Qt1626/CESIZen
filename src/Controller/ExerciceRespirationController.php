<?php

namespace App\Controller;

use App\Repository\ExerciceRespirationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/exercice_respiration')]
final class ExerciceRespirationController extends AbstractController
{
    /**
     * @var ExerciceRespirationRepository $exercice_respirationRepository
     */
    private $exercice_respirationRepository;

    public function __construct(ExerciceRespirationRepository $exercice_respirationRepository)
    {
        $this->exercice_respirationRepository = $exercice_respirationRepository;
    }

    #[Route('/', name: 'app_exercice_respiration')]
    public function index(): Response
    {
        // Passer les ressources à la vue Twig
        return $this->render('ressource/exercice_respiration.html.twig', [
            'controller_name' => 'ExerciceRespirationController',
            // Récupérer toutes les ressources depuis la base de données
            'exercice_respirations' => $this->exercice_respirationRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_exercice_respiration_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        return $this->render('ressource/exercice_respiration.html.twig', [
            'exercice_respiration' => $this->exercice_respirationRepository->find($id),
        ]);
    }
}


