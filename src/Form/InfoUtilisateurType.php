<?php

namespace App\Form;

use App\Entity\InfoUtilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InfoUtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class, ['label' => 'Nom'])
            ->add('prenom',TextType::class, ['label' => 'Prénom'])
            ->add('dateNaissance', null, [
                'widget' => 'single_text', 'label' => 'Date de naissance',
            ])
            ->add('email',EmailType::class, ['label' => 'Adresse e-mail', 'help' => 'Cette adresse servira à vous connecter.'])
            ->add('motDePasse', PasswordType::class, ['label' => 'Mot de passe'])
            ->add('consentement', CheckboxType::class, ['mapped' => false, 'label' => "J'accepte les conditions générales"])
            ->add('save', SubmitType::class, ['label' => "S'inscrire"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InfoUtilisateur::class
        ]);
    }
}
