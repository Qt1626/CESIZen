<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;

final class InfoConnexionController extends AbstractController{

    #[Route('/info', name: 'app_info_connexion')]

    public function index(): Response

    {

        return $this->render('connection/info_connection.html.twig', [

            'controller_name' => 'profileController',

        ]);

    }

}

