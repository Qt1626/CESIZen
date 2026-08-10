<?php

namespace App\Factory;

use App\Entity\InformationPage;
use App\Repository\InformationPageRepository;
use Doctrine\ORM\EntityRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<InformationPage>
 */
final class InformationPageFactory extends PersistentProxyObjectFactory{
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
        return InformationPage::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'Commentaire' => self::faker()->text(255),
            'ContenuPage' => self::faker()->text(1024),
            'EstVisible' => self::faker()->boolean(),
            'OrdreAffichage' => self::faker()->numberBetween(1,500),
            'TitrePage' => self::faker()->text(50),
            'Image' => 'https://picsum.photos/450/225'
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(InformationPage $informationPage): void {})
        ;
    }
}
