# Système de Gestion de contact
## Technologie Utilisées

- Base de données : MysQL
- Cache : Redis
- Intégration Api : API Découpage Administratif - (API Geo) [documentations](https://api.gouv.fr/documentation/api-geo)
- PHP 8.1

## Description
Projet bac à sable réalisé en PHP natif. Il s'agit d'une application de gestion de contacts, conçue dans le but d'expérimenter le fonctionnement des frameworks MVC en tentant d'en reproduire un de manière artisanale.

Le développement du mini-framework est encore en cours et comporte plusieurs points à améliorer.
En revanche, le CRUD est entièrement fonctionnel. L'application permet :

d'afficher la liste des contacts,

de consulter les détails d'un contact,

d'ajouter un nouveau contact,

de modifier un contact existant,

de supprimer un contact,

d'ajouter un département à un contact via l’API Geo.

Note : il n’y a pas de système d’authentification intégré pour le moment.
