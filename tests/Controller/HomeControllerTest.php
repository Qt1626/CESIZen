<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testHomePageIsSuccessful(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }

    public function testLoginPageIsSuccessful(): void
    {
        $client = static::createClient();

        $client->request('GET', '/connexion');

        $this->assertResponseIsSuccessful();
    }

    public function testRegisterPageIsSuccessful(): void
    {
        $client = static::createClient();

        $client->request('GET', '/inscription');

        $this->assertResponseIsSuccessful();
    }
}