<?php
include_once 'src/Service/APIGeoLoc.php';
function validateNomEtPrenom(string $nom): bool
{
    if (empty($nom)) {
        return false;
    }

    $longueur = strlen($nom);
    if ($longueur < 3 || $longueur > 100) {
        return false;
    }

    if (!preg_match('/^[\p{L}0\- ]+$/u', $nom)) {
        return false;
    }

    return true;
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}


function validatePhone($phoneNumber)
{
    // Utilisation d'une expression régulière pour vérifier le format du numéro de téléphone
    $pattern = '/^\+?[0-9]{1,4}(\s?[0-9]){6,14}$/';

    // Vérifier si la chaîne correspond à l'expression régulière
    return preg_match($pattern, $phoneNumber) === 1;
}

function validateDepartement($departement) {
    $departement = strtolower(trim($departement));
    $APIGeoLoc = APIGeoLoc::getInstance();
    $reponse = $APIGeoLoc->getDepartementByNom($departement);

    //Si problème avec l'api on ne bloque pas l'application.
    if(isset($reponse['error'])) {
        return true;
    }

    foreach ($reponse as $d) {
        if(strtolower($d['nom']) == ($departement)) {
            return true;
        }
    }

    return false;
}

function validateCommune($commune) {
    $departement = strtolower(trim($commune));
    $APIGeoLoc = APIGeoLoc::getInstance();
    $reponse =$APIGeoLoc->getCommuneByNom($commune);

    //Si problème avec l'api on ne bloque pas l'application.
    if(isset($reponse['error'])) {
        return true;
    }

    /** @var Commune $c */
    foreach ($reponse as $c) {
        if(strtolower($c->nom) == ($commune)) {
            return true;
        }
    }

    return false;
}


