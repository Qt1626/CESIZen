<?php

namespace App\Controller;

use Error;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RegisterController extends AbstractController{
    #[Route('/inscriptiondddd', name: 'app_register')]
    public function index(Request $request): Response
    {

        if ($request != null) {
 //           throw new Error($request);
        }

        return $this->render('register/register.html.twig', [
            'controller_name' => 'registerController',
        ]);
    }


    #[Route('/inscriptionddd', name: 'registerSubmit')]
    public function submit(Request $request, LoggerInterface $logger): Response
    {
        $logger->info('On est ici');
        $logger->error('Grave');
        throw new Error('ICI');
        $username = $request->request->get('_username');
        $password = $request->request->get('_password');

        return $this->render('register/register.html.twig', [
            'controller_name' => 'registerController',
        ]);
    }


    #[Route('/inscription/test', name: 'app_test')]
    public function test(){
        echo "test";
        return new Response(

            '<html><body>Lucky number: '.'</body></html>'

        );
    }
}
