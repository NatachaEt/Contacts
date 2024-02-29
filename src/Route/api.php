<?php

function routeAPI($method,$path)
{

    if(strpos($path, '/departement') !== false) {
        $APIGeoLoc = APIGeoLoc::getInstance();
        if ($method === 'GET') {
            $name = "";
            if(isset($_GET['name'])){
                $name = htmlspecialchars($_GET['name']);
                $name = trim($name);
                if(!preg_match("/^[a-zA-Z ]+$/", $name) & !empty($name)) {
                    http_response_code(500);
                    echo "Format de nom non valide.";
                }
            }

            $reponse = "";

            if(empty($name)){
                $reponse = $APIGeoLoc->getDepartement();
            } else {
                $reponse = $APIGeoLoc->getDepartementByNom($name);
            }

            header('Content-Type: application/json');
            echo json_encode($reponse);
        }
    }

    return '';
}
