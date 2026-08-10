<?php

namespace App\Controller\Admin;

use App\Entity\InfoUtilisateur;
use App\Entity\Utilisateur;
use Composer\InstalledVersions;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class InfoUtilisateurCrudController extends AbstractCrudController
{
    public function __construct(
        public UserPasswordHasherInterface $userPasswordHasher,
        public LoggerInterface $logger,
    ) {}
    public static function getEntityFqcn(): string
    {
        return InfoUtilisateur::class;
    }

    public function configureCrud(Crud $crud): \EasyCorp\Bundle\EasyAdminBundle\Config\Crud
    {
        return parent::configureCrud($crud)->showEntityActionsInlined();
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->setDisabled(),
            TextField::new('nom'),
            TextField::new('prenom')->setLabel('Prénom'),
            EmailField::new('email')->hideOnIndex(),
            TextField::new('motDePasse')->hideOnIndex(),
            AssociationField::new('utilisateur')->onlyOnIndex()->setLabel('Email'),
        ];
    }

}
