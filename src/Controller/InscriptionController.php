<?php

namespace App\Controller;

use App\Entity\InfoUtilisateur;
use App\Entity\Utilisateur;
use App\Form\InfoUtilisateurType;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'infoUtilisateur_add')]
    public function addInfoUtilisateur(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger , UserPasswordHasherInterface $passwordHasher)
    {


        $infoUtilisateur = new InfoUtilisateur();
        $form = $this->createForm(InfoUtilisateurType::class, $infoUtilisateur);


  //      $retour = $this->testGet($logger);
  //      $logger->info($retour);



        $logger->info('AVANT SAVE');
        $repository = $entityManager->getRepository(InfoUtilisateur::class);
        $question = $repository->findOneBy(['id' => '50']);
        $logger->info($question->getNom());
/*
        $utilisateur = new Utilisateur();
        $utilisateur->setDateCreation(new DateTimeImmutable('now', new DateTimeZone('Europe/Paris')));
        $utilisateur->setConsentementDonne(false);
        $utilisateur->setEstAdmin(false);
        $utilisateur->setInfoUtilisateur($question);
        $entityManager->persist($utilisateur);
        $entityManager->flush();
*/
//        $logger->info($utilisateur->getDateCreation()->format('Y-m-d H:i:s'));

        $logger->info('APRES SAVE');

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $consentement = $form->get('consentement')->getData();
            $logger->info('Valeur checkbox après Submit : ' . ($consentement ? 'true' : 'false') );

            $plaintextPassword = $infoUtilisateur->getPassword();
            //            $logger->info('MDP clair = ' . $plaintextPassword);
                        $hashedPassword = $passwordHasher->hashPassword($infoUtilisateur, $plaintextPassword);
                        $infoUtilisateur->setMotDePasse($hashedPassword);
            //            $logger->info('MDP crypte 1 = ' . $hashedPassword);//
            //            $logger->info('MDP crypte 2 = ' . $infoUtilisateur->getPassword());


            $entityManager->persist($infoUtilisateur);
            $entityManager->flush();

            $utilisateur = new Utilisateur();
            $utilisateur->setDateCreation(new DateTimeImmutable('now', new DateTimeZone('Europe/Paris')));
            $utilisateur->setConsentementDonne($consentement);
            $utilisateur->setEstAdmin(false);
            $utilisateur->setInfoUtilisateur($infoUtilisateur);
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur ' . $infoUtilisateur->getEmail() . ' créé avec succès !');

            return $this->redirectToRoute('app_acceuil');
        }

        return $this->render('register/register.html.twig', [
            'form' => $form
        ]);
    }


    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testGet(LoggerInterface $logger): string
    {
        $logger->info("1");
  /*      $response = $this->client->request(
            'GET',
            'http://localhost:8000/api/info_utilisateurs/5', [
            'headers' => [
        'Content-Type' => 'application/ld+json',
             ],
            ]
        );
  */

        $response = $this->client->request(
            'GET',
            'https://api.github.com/repos/symfony/symfony-docs'
        );

        $logger->info("2");


        $response = $this->client->request(
            'GET',
            'http://127.0.0.1:8000/api/info_utilisateurs'
        );

        $logger->info("2 bis");


        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $logger->info("3");
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
      //  $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        return $content;
    }
}