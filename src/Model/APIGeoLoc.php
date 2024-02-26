<?php

class APIGeoLoc
{
    private static $instance;
    private string $endpoint = "https://geo.api.gouv.fr";

    private function __construct() {
        $curl = curl_init();
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    public function getDepartementByNom($name) {
        $url = $this->endpoint.'/departements?nom='.$name.'fields=nom';
    }



}
