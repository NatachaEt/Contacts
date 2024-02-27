<?php

class APIGeoLoc
{
    private static $instance;
    private string $endpoint = "https://geo.api.gouv.fr";
    static string $namespace = "APIGeoloc";

    private function __construct()
    {
        $curl = curl_init();
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getDepartementByNom($name) : array
    {
        $url = $this->endpoint.'/departements?nom='.$name.'fields=nom';
        return $this->getReponse($url);
    }

    public function getCommuneByNom($name) : array
    {
        $url = $this->endpoint.'/communes?nom='.$name.'&fields=nom&format=json&geometry=centre';
        return $this->getReponse($url);
    }

    private function getReponse($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);

        $data = [];

        if ($response == false) {
            $e = curl_error($curl);
            $data = gestionErreur($e,self::$namespace,'curl');
        } else {
            $data = json_decode($response, true);
        }

        curl_close($curl);
        return $data;
    }
}

class Departement {
    public string $nom;
    public string $code;
    public string $score;

    public function __construct()
    {}
}

class Commune extends Departement
{}
