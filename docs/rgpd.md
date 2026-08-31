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

## 7. Améliorations prévues

Pour une mise en production réelle, CESIZen devrait également intégrer :

- une politique de confidentialité complète ;
- une gestion explicite du consentement lorsque nécessaire ;
- une procédure automatisée de suppression des comptes ;
- une politique de conservation des données ;
- un registre des traitements.