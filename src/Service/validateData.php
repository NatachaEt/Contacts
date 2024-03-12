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
    $pattern = '/^(?:\+33|0)[1-9](?:(?:\s?\d){8}|(?:-?\d){8})$/';

    // Vérifier si la chaîne correspond à l'expression régulière
    return preg_match($pattern, $phoneNumber) === 1;
}

function validateDepartement($departement) {
    $departement = strtolower(trim($departement));
    $APIGeoLoc = APIGeoLoc::getInstance();
    $reponse = $APIGeoLoc->getDepartementByNom($departement);

    //Si problème avec l'api on ne bloque pas l'application.
    if(isset($reponse['error'])) {
        $pattern = '/\b(?:Ain|Aisne|Allier|Alpes-de-Haute-Provence|Hautes-Alpes|Alpes-Maritimes|Ardèche|Ardennes|Ariège|Aube|Aude|Aveyron|Bouches-du-Rhône|Calvados|Cantal|Charente|Charente-Maritime|Cher|Corrèze|Corse-du-Sud|Haute-Corse|Côte-d\'Or|Côtes-d\'Armor|Creuse|Dordogne|Doubs|Drôme|Eure|Eure-et-Loir|Finistère|Gard|Haute-Garonne|Gers|Gironde|Hérault|Ille-et-Vilaine|Indre|Indre-et-Loire|Isère|Jura|Landes|Loir-et-Cher|Loire|Haute-Loire|Loire-Atlantique|Loiret|Lot|Lot-et-Garonne|Lozère|Maine-et-Loire|Manche|Marne|Haute-Marne|Mayenne|Meurthe-et-Moselle|Meuse|Morbihan|Moselle|Nièvre|Nord|Oise|Orne|Pas-de-Calais|Puy-de-Dôme|Pyrénées-Atlantiques|Hautes-Pyrénées|Pyrénées-Orientales|Bas-Rhin|Haut-Rhin|Rhône|Haute-Saône|Saône-et-Loire|Sarthe|Savoie|Haute-Savoie|Paris|Seine-Maritime|Seine-et-Marne|Yvelines|Deux-Sèvres|Somme|Tarn|Tarn-et-Garonne|Var|Vaucluse|Vendée|Vienne|Haute-Vienne|Vosges|Yonne|Territoire de Belfort|Essonne|Hauts-de-Seine|Seine-Saint-Denis|Val-de-Marne|Val-d\'Oise)\b/i';

        return preg_match($pattern, $departement) === 1;
    }

    foreach ($reponse as $d) {
        if(strtolower($d['nom']) == $departement) {
            return true;
        }
    }

    return false;
}

function validateCommune($commune) {
    $commune = strtolower(trim($commune));
    $APIGeoLoc = APIGeoLoc::getInstance();
    $reponse = $APIGeoLoc->getCommuneByNom($commune);

    //Si problème avec l'api on ne bloque pas l'application.
    if(isset($reponse['error'])) {
        return true;
    }

    foreach ($reponse as $c) {
        if(strtolower($c["nom"]) == $commune) {
            return true;
        }
    }

    return false;
}

function validateCommuneInDepartement($commune,$departement) {
    $commune = strtolower(trim($commune));
    $departement = strtolower(trim($departement));
    $APIGeoLoc = APIGeoLoc::getInstance();
    $reponse = $APIGeoLoc->getCommuneInfoByNom($commune);

    //Si problème avec l'api on ne bloque pas l'application.
    if(empty($reponse) ||  isset($reponse['error'])) {
        return true;
    }

    foreach ($reponse as $c) {
        if(strtolower($c["nom"]) == $commune && strtolower($c["departement"]["nom"]) == $departement ) {
            return true;
        }
    }

    return false;
}
