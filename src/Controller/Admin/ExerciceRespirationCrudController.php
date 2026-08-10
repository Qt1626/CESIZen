<?php

namespace App\Controller\Admin;

use App\Entity\ExerciceRespiration;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ExerciceRespirationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ExerciceRespiration::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->setDisabled(),
            TextField::new('nomExerciceRespiration'),
            TextField::new('descriptionExerciceRespiration'),
            NumberField::new('dureeInspiration'),
            NumberField::new('dureeApnee'),
            NumberField::new('dureeExpiration'),
        ];
    }

}
