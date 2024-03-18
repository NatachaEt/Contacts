# Système de Gestion de contact
## Technologie Utilisées

- Base de données : MysQL
- Cache : Redis
- Intégration Api : API Découpage Administratif - (API Geo) [documentations](https://api.gouv.fr/documentation/api-geo)
- PHP 8.1

## Description
Application de gestion de contact fait en php pur. 
Le but était de voir le fonctionnement des frameworks de type MVC 
et d'essayer de recréer un. Le projet de framework n'est pas terminé et 
il reste pleins de points à améliorer. Le crud est totalement fonctionnel.
Il est possible de :
- Voir tous les contacts
- Voir un contact
- Ajouter un contact
- Modifier un contact
- Supprimer un contact
- Ajouter un departement a un contact a l'aide de l'api geo.

Il n'y a pas de système d'authentification car pour l'instant c'est un projet bac à sable déstiné a une utilisation local.

## Diagrammes 
[UseCase](./diagrammeUML/UseCase.png)
[DiagrammeActivité](./diagrammeUML/diagrammeActivite.png)
[Flux](./diagrammeUML/Flux.png)

## Estimation des coûts sur AWS
    Instance EC2 (t3.small) en France :
        Coût horaire : Environ 0,0416 USD 
        Coût mensuel (24/7) : Environ 30 USD.

    Instance RDS (MySQL) avec 1 Go de stockage :
        Coût horaire : Environ 0,017 USD pour l'instance (tarif de base) + coût additionnel pour le stockage.
        Coût mensuel (24/7) : Environ 12 USD pour l'instance + coût additionnel pour le stockage.

    Cluster Elasticache pour Redis :
        Le coût dépend de la taille du cluster et de la classe d'instance. Pour une petite utilisation et une petite mémoire allouée, nous utiliserons une instance de classe cache.t3.micro.
        Coût horaire : Environ 0,012 USD pour une instance de cache.t3.micro (selon les tarifs AWS au moment de l'estimation).
        Coût mensuel (24/7) : Environ 8,64 USD.

    Frais d'utilisation de Redis :
        Les frais supplémentaires pour les requêtes et la petite taille de données devraient être minimes, mais peuvent varier en fonction de l'utilisation réelle. Nous pourrions estimer un coût supplémentaire mensuel de quelques dollars, mais cela peut être négligeable pour une utilisation légère.

Total mensuel estimé (sans les frais d'utilisation de Redis) : Environ 50,64 USD soit 46,49 euro.
