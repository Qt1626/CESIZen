# Prise en compte du RGPD - CESIZen

## 1. Données personnelles traitées

L'application CESIZen peut traiter des données personnelles liées aux utilisateurs, notamment :

- nom ;
- prénom ;
- adresse e-mail ;
- date de naissance ;
- informations de connexion ;
- données associées au compte utilisateur.

## 2. Minimisation des données

Seules les données nécessaires au fonctionnement de l'application doivent être collectées.

Les données inutiles au fonctionnement de CESIZen ne doivent pas être conservées.

## 3. Sécurité des données

Plusieurs mesures permettent de protéger les données :

- accès à la base PostgreSQL protégé ;
- secrets stockés dans GitHub Secrets et Render ;
- utilisation de HTTPS sur Render ;
- contrôle des accès ;
- audit automatique des dépendances ;
- sauvegarde de la base de données ;
- procédure de gestion des incidents.

## 4. Droits des utilisateurs

Conformément au RGPD, un utilisateur doit pouvoir demander :

- l'accès à ses données ;
- leur rectification ;
- leur suppression ;
- leur portabilité lorsque cela est applicable.

## 5. Durée de conservation

Les données ne doivent pas être conservées plus longtemps que nécessaire.

Une politique de conservation doit définir les durées adaptées aux différentes catégories de données.

## 6. Incident impliquant des données personnelles

En cas de fuite ou de compromission de données :

1. identifier les données concernées ;
2. limiter l'incident ;
3. déterminer les utilisateurs concernés ;
4. informer le responsable du projet ;
5. évaluer la nécessité d'une notification à la CNIL ;
6. informer les personnes concernées lorsque le risque le justifie ;
7. documenter l'incident et les actions réalisées.

## 7. Catégorisation des données de santé (article 9 RGPD)

Les exercices de respiration associés à un utilisateur (table `exercice_respiration_utilisateur`) peuvent, par recoupement, révéler des éléments relatifs à sa santé mentale. Ces données doivent donc être traitées comme une **catégorie particulière de données** au sens de l'article 9 du RGPD, nécessitant une base légale renforcée.

Sur le plan technique, cette exigence est déjà en grande partie couverte :

- le consentement est recueilli explicitement à l'inscription et stocké (`Utilisateur::$consentementDonne`, voir `InscriptionController`) ;
- l'accès aux pages `/info` est réservé aux utilisateurs authentifiés (`config/packages/security.yaml`) ;
- l'accès au back-office (consultation de l'ensemble des utilisateurs et de leurs exercices) est réservé au rôle `ROLE_ADMIN`.

La formalisation juridique et documentaire de cette base légale renforcée est la suivante.

**Mention explicite à l'inscription.** Le formulaire d'inscription doit présenter, à côté de la case à cocher liée à `consentementDonne`, le texte suivant :

> « En cochant cette case, vous consentez à ce que CESIZen traite les données associées aux exercices de respiration que vous pratiquez (fréquence, type d'exercice) à des fins de suivi personnel. Ces données, dans la mesure où elles peuvent révéler des éléments relatifs à votre santé mentale, constituent une catégorie particulière de données au sens de l'article 9 du RGPD. Elles ne sont accessibles qu'à vous-même et, à des fins de support, à l'administrateur de la plateforme. Vous pouvez retirer ce consentement à tout moment depuis votre espace compte, sans que cela n'affecte la suppression de votre compte lui-même. »

**Retrait du consentement.** Une action « Retirer mon consentement » doit être ajoutée à l'espace compte utilisateur. Elle repasse `consentementDonne` à `false` et désactive l'enregistrement de nouveaux exercices de respiration, sans supprimer le compte ni l'historique déjà consenti (conservé jusqu'à une éventuelle suppression de compte, conformément à la section 5).

**Registre des traitements (extrait).**

| Traitement | Finalité | Base légale | Catégories de données | Durée de conservation | Destinataires |
|---|---|---|---|---|---|
| Compte utilisateur | Authentification et accès à l'application | Exécution du contrat (CGU) | Identité, e-mail, mot de passe (haché) | Durée de vie du compte + 12 mois | Équipe technique (Qt1626) |
| Suivi des exercices de respiration | Suivi personnel de l'usage de l'application | Consentement explicite (art. 9 RGPD, donnée de santé) | Exercices pratiqués, dates, fréquence | Durée de vie du compte, ou jusqu'à retrait du consentement | Utilisateur lui-même, administrateur (support) |
| Journal d'administration (`admin_log`) | Traçabilité des actions d'administration | Intérêt légitime (sécurité) | Type d'action, date, identifiant administrateur | 12 mois glissants | Équipe technique (Qt1626) |

Responsable du traitement : Quentin Thil, porteur du projet CESIZen (contact : voir profil GitHub [Qt1626](https://github.com/Qt1626)).

## 8. Améliorations prévues

Pour une mise en production réelle avec de vrais utilisateurs, CESIZen devrait encore intégrer :

- une politique de confidentialité complète (au-delà de la mention et du registre ci-dessus) ;
- une procédure automatisée de suppression des comptes après la durée de conservation définie ;
- une politique de conservation formalisée pour chaque catégorie de données du registre.
