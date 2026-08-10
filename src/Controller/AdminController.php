<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController{

    #[Route('/admin_old', name: 'app_admin')]

    public function index(): Response

    {

        return $this->render('admin/dashboard.html.twig', [

            'controller_name' => 'profileController',

        ]);

    }

}

