<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class SecurityHeadersSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly string $environment,
    ) {
    }

    /**
     * Force HTTPS en production : redirige toute requête HTTP vers son
     * équivalent HTTPS avant même qu'elle n'atteigne l'application. Le header
     * Strict-Transport-Security (ci-dessous) protège les visites suivantes,
     * mais pas la toute première requête d'un navigateur — d'où cette
     * redirection explicite. Voir docs/security-plan.md.
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest() || 'prod' !== $this->environment) {
            return;
        }

        $request = $event->getRequest();

        if ($request->isSecure()) {
            return;
        }

        $httpsUrl = 'https://' . $request->getHttpHost() . $request->getRequestUri();
        $event->setResponse(new RedirectResponse($httpsUrl, RedirectResponse::HTTP_MOVED_PERMANENTLY));
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $response = $event->getResponse();

        // Empêche le navigateur de deviner le type MIME
        $response->headers->set(
            'X-Content-Type-Options',
            'nosniff'
        );

        // Protection contre le clickjacking
        $response->headers->set(
            'X-Frame-Options',
            'DENY'
        );

        // Limite les informations envoyées dans le Referer
        $response->headers->set(
            'Referrer-Policy',
            'strict-origin-when-cross-origin'
        );

        // Désactive les fonctionnalités inutilisées
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=()'
        );

        // Force l'utilisation de HTTPS
        $response->headers->set(
            'Strict-Transport-Security',
            'max-age=31536000; includeSubDomains'
        );
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }
}
