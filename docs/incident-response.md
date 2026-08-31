\# Procédure de gestion d'un incident de sécurité - CESIZen



\## Objectif



Cette procédure définit les actions à appliquer lorsqu'un incident de sécurité affecte l'application CESIZen, son infrastructure ou les données utilisateurs.



\## 1. Détection de l'incident



Un incident peut être détecté par :

\- les logs de l'application ;

\- les logs Render ;

\- les erreurs remontées par les utilisateurs ;

\- GitHub Actions ;

\- l'audit des dépendances Composer ;

\- une alerte concernant une vulnérabilité.



\## 2. Qualification



L'incident est classé selon son niveau de priorité :



\- P1 - Critique : service indisponible, fuite de données ou compromission majeure.

\- P2 - Haute : vulnérabilité importante ou fonctionnalité essentielle indisponible.

\- P3 - Normale : incident avec impact limité.

\- P4 - Faible : incident mineur sans impact important.



Un ticket GitHub est créé avec le label `security` et le niveau de priorité correspondant.



\## 3. Confinement



L'objectif est de limiter rapidement l'impact de l'incident.



Actions possibles :

\- désactivation temporaire d'une fonctionnalité ;

\- rotation d'un secret compromis ;

\- blocage d'un accès ;

\- retour à une version Docker précédente ;

\- isolation du composant concerné.



\## 4. Analyse et correction



Les logs et les changements récents sont analysés afin d'identifier la cause.



La correction est réalisée sur une branche Git dédiée.



La Pull Request doit ensuite passer par la CI avant d'être fusionnée sur `master`.



\## 5. Déploiement du correctif



Après validation de la CI :



1\. la Pull Request est fusionnée ;

2\. GitHub Actions construit une nouvelle image Docker ;

3\. l'image est publiée dans GitHub Container Registry ;

4\. Render est automatiquement redéployé ;

5\. le fonctionnement de l'application est contrôlé.



\## 6. Restauration



En cas de problème sur la base PostgreSQL, une sauvegarde peut être utilisée pour restaurer les données.



Une restauration doit être testée avant de considérer l'incident comme résolu.



\## 7. Communication et escalade



Pour un incident P3 ou P4 :

\- suivi dans GitHub Issues ;

\- correction par l'équipe technique.



Pour un incident P2 :

\- information du responsable du projet ;

\- suivi renforcé jusqu'à résolution.



Pour un incident P1 :

\- information immédiate du responsable du projet ;

\- interruption éventuelle du service ;

\- analyse de l'impact sur les utilisateurs et les données ;

\- déclenchement de la procédure RGPD si des données personnelles sont concernées.



\## 8. Clôture



Après résolution :

\- vérifier que le service fonctionne ;

\- fermer le ticket GitHub ;

\- documenter la cause ;

\- documenter la correction ;

\- proposer une action préventive pour éviter une récidive.

