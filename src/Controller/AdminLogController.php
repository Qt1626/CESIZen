<?php

namespace App\Controller;

use App\Entity\AdminLog;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\AdminLogType;

final class AdminLogController extends AbstractController{


    #[Route('/admin/log', name: 'app_admin_log')]
    public function index(Request $request,  EntityManagerInterface $entityManager, LoggerInterface $logger): \Symfony\Component\HttpFoundation\Response
    {
        $adminLog = new AdminLog();
        $form = $this->createForm(AdminLogType::class, $adminLog);

        $logger->info("ICI 01");

        $form->handleRequest($request);
        if ($form->isSubmitted()  && $form->isValid()) {
            // Traitement du formulaire...

            $logger->info("ICI 02");

            $entityManager->persist($adminLog);
            $entityManager->flush();
            return $this->redirectToRoute('app_acceuil');

        }

        $logger->info("ICI 03");

        return $this->render('login/login.html.twig', [
            'form' => $form]);
    }
}
