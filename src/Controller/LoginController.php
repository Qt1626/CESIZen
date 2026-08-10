<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class LoginController extends AbstractController
{
    #[Route('/connexion', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils, LoggerInterface $logger): Response

    {

        // get the login error if there is one

        $error = $authenticationUtils->getLastAuthenticationError();
        $logger->info('error = ' . $error);
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $logger->info('lastUserName = ' . $lastUsername);
        //   $this->addFlash('success', 'Utilisateur ' . $authenticationUtils->getLastUsername() . ' connecté avec succès !');

        return $this->render('login/login.html.twig', [
            'controller_name' => 'LoginController',
            'last_username' => $lastUsername,
            'error' => $error,

        ]);

    }
}
