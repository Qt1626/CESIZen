<?php

namespace App\Factory;

use App\Entity\InfoUtilisateur;
use App\Repository\InfoUtilisateurRepository;
use Doctrine\ORM\EntityRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<InfoUtilisateur>
 */
final class InfoUtilisateurFactory extends PersistentProxyObjectFactory{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return InfoUtilisateur::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'Email' => self::faker()->unique()->email(),
            'MotDePasse' => self::faker()->text(255),
            'DateNaissance' => self::faker()->dateTime(),
            'Nom' => self::faker()->text(255),
            'Prenom' => self::faker()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(InfoUtilisateur $infoUtilisateur): void {})
        ;
    }
}
