<?php

function gestionErreur(Exception $e, string $namespace, string $bdd = ''): array
{
    if($bdd == 'redis') {
        return ['error' => 'Erreur redis dans '. $namespace . ' : ' . $e->getMessage()];
    }
    if($bdd == 'mySql') {
        return ['error' => 'Erreur mySql dans '. $namespace . ' : ' . $e->getMessage()];
    }
    if($namespace == Bdd::$namespaceErreur) {
        return ['error' => 'Erreur de connexion à Redis : ' . $e->getMessage()];
    }
    if($namespace == Utilisateur::$namespace.'_getAll') {
        return ['error' => 'Erreur récupérations des contacts : ' . $e->getMessage()];
    }
    if($namespace == Utilisateur::$namespace.'add') {
            return ['error' => 'Erreur création d\'un contact : ' . $e->getMessage()];
    }
    if($namespace == Utilisateur::$namespace.'delete') {
        return ['error' => 'Erreur suppression d\'un contact : ' . $e->getMessage()];
    }
    if($namespace == Utilisateur::$namespace.'get') {
        return ['error' => 'Erreur récupération d\'un contact : ' . $e->getMessage()];
    }
    if($namespace == Utilisateur::$namespace.'put') {
        return ['error' => 'Erreur modification d\'un contact : ' . $e->getMessage()];
    }
    if($namespace == 'generateCrf') {
        return ['error' => 'Erreur jeton csrf veuillez recharger la page'];
    }

    return ['error' => $e->getMessage()];
}
