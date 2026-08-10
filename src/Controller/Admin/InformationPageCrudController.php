<?php

namespace App\Controller\Admin;

use App\Entity\InformationPage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Faker\Core\Number;

class InformationPageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return InformationPage::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->setDisabled(),
            TextField::new('titrePage'),
            NumberField::new('ordreAffichage'),
            BooleanField::new('estVisible'),
            TextField::new('contenuPage'),
            TextField::new('commentaire'),
            ImageField::new('image')->onlyOnIndex(),
            TextField::new('image')->onlyOnForms(),

        ];
    }

}
