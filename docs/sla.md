# Engagement de niveau de service (SLA) - CESIZen

Ce document formalise, pour chaque niveau de criticité, le délai de prise en charge (accusé de réception par le prestataire) et le délai de résolution cible vis-à-vis du client. Il reprend l'échelle de priorité utilisée pour le ticketing (docs/ticketing.md) et la gestion d'incident (docs/incident-response.md), afin que l'ensemble de la documentation reste cohérent.

## Niveaux de service

| Niveau | Exemple | Prise en charge | Résolution cible |
|--------|---------|------------------|-------------------|
| **P1 – Critique** | Service indisponible, fuite de données, compromission | 1 heure | 4 heures |
| **P2 – Haute** | Fonctionnalité essentielle indisponible, vulnérabilité importante | 4 heures ouvrées | 1 jour ouvré |
| **P3 – Normale** | Anomalie à impact limité, non bloquante | 1 jour ouvré | 5 jours ouvrés |
| **P4 – Faible** | Anomalie cosmétique, amélioration mineure | 3 jours ouvrés | Planifiée sur la prochaine itération |

*Heures ouvrées : jours ouvrés, 9h-18h heure de Paris, hors jours fériés.*

## Attribution de la priorité

La priorité d'un ticket est déterminée à sa qualification (docs/ticketing.md) selon la nature de l'anomalie et son impact, en s'appuyant sur la même échelle que la procédure de gestion d'incident (docs/incident-response.md, section 2 « Qualification »). Les tickets ouverts automatiquement (voir ci-dessous) reçoivent une priorité par défaut, ajustable après qualification humaine.

| Source du ticket | Priorité par défaut |
|---|---|
| Échec de la CI (`auto-ticket.yml`) | P2 |
| Échec de la CD / du déploiement (`auto-ticket.yml`) | P1 |
| Échec de la reconstruction nocturne de l'environnement dev (`auto-ticket.yml`) | P3 |
| Indisponibilité confirmée du service (`uptime-check.yml`) | P1 |
| Vulnérabilité détectée (Trivy/Gitleaks/composer audit/ZAP) | P1 si CRITICAL, P2 si HIGH |

## Portée et limites

Ces seuils sont proposés pour un contexte d'application étudiante à fort enjeu de démonstration. Pour un client réel, ils doivent être :

- ajustés selon les horaires réels d'astreinte disponibles ;
- formellement contractualisés (avec, le cas échéant, des pénalités en cas de non-respect) ;
- révisés périodiquement en fonction du retour d'expérience (nombre de tickets, délais réellement tenus).
