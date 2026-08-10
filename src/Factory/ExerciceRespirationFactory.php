<?php

namespace App\Factory;

use App\Entity\ExerciceRespiration;
use App\Repository\ExerciceRespirationRepository;
use Doctrine\ORM\EntityRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<ExerciceRespiration>
 */
final class ExerciceRespirationFactory extends PersistentProxyObjectFactory{
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
        return ExerciceRespiration::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [

            'NomExerciceRespiration' => self::faker()->text(30),
            'DescriptionExerciceRespiration'  => self::faker()->text(200),
            'DureeInspiration' => self::faker()->randomDigit(),
            'DureeApnee' => self::faker()->randomDigit(),
            'DureeExpiration' => self::faker()->randomDigit()
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(ExerciceRespiration $exerciceRespiration): void {})
        ;
    }
}
