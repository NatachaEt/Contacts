<?php
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

}



