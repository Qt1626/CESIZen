<?php

namespace App\Factory;

use App\Entity\Utilisateur;
use App\Entity\InfoUtilisateur;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

final class UtilisateurFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Utilisateur::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'EstAdmin' => self::faker()->boolean(),
            'ConsentementDonne' => self::faker()->boolean(),
            'DateCreation' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),

        ];
    }

    protected function initialize(): static
    {
        return $this;
    }
}
