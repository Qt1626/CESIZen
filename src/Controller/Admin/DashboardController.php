<?php

namespace App\Controller\Admin;

use App\Entity\AdminLog;
use App\Entity\ExerciceRespiration;
use App\Entity\InformationPage;
use App\Entity\InfoUtilisateur;
use App\Entity\Utilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {




         $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
         return $this->redirect($adminUrlGenerator->setController(InfoUtilisateurCrudController::class)->generateUrl());


    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Projet Etat ');
    }

    public function configureMenuItems(): iterable
    {
        // yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToRoute('Retour au site', 'fas fa-home', 'app_acceuil');
        yield MenuItem::linkToCrud('Info Utilisateurs', 'fas fa-user', InfoUtilisateur::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user-secret', Utilisateur::class);
        yield MenuItem::linkToCrud('Pages Information', 'fas fa-building', InformationPage::class);
        yield MenuItem::linkToCrud('Exercices Respiration', 'fas fa-list', ExerciceRespiration::class);
        yield MenuItem::linkToCrud('Logs Admin', 'fas fa-info-circle', AdminLog::class);

    }
}
