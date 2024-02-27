<?php

function routeAPI($method,$url)
{
    $url_components = parse_url($url);
    $path = $url_components['path'];

    if(strpos($path, '/departement') === 0) {
        if ($method === 'GET') {
            $APIGeoLoc = APIGeoLoc::getInstance();
            parse_str($url_components['query'], $query_params);
            echo "<pre>";
            var_dump($query_params);
            echo "</pre>";

            //return $APIGeoLoc->getDepartementByNom();
        }
    }

    return '';
}
