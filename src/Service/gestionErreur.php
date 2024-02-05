<?php

function gestionErreur(Exception $e, String $namespace): array
{
    if($namespace == Bdd::$namespaceErreur) {
        return ['error' => 'Erreur de connexion à Redis : ' . $e->getMessage()];
    }
    if($namespace == Utilisateur::$namespaceUtilisateur.'_getAll') {
        return ['error' => 'Erreur récupérations des contacts : ' . $e->getMessage()];
    }
    if($namespace == Utilisateur::$namespaceUtilisateur.'add') {
            return ['error' => 'Erreur création d\'un contact : ' . $e->getMessage()];
    }
    if($namespace == Utilisateur::$namespaceUtilisateur.'delete') {
        return ['error' => 'Erreur suppression d\'un contact : ' . $e->getMessage()];
    }
    if($namespace == Utilisateur::$namespaceUtilisateur.'get') {
        return ['error' => 'Erreur récupération d\'un contact : ' . $e->getMessage()];
    }
    if($namespace == Utilisateur::$namespaceUtilisateur.'put') {
        return ['error' => 'Erreur modification d\'un contact : ' . $e->getMessage()];
    }
    if($namespace == 'generateCrf') {
        return ['error' => 'Erreur jeton csrf veuillez recharger la page'];
    }

    return ['error' => $e->getMessage()];
}
