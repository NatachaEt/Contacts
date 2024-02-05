<?php
include_once 'src/Service/gestionErreur.php';
include_once  'src/Service/History.php';


class Security {
    public function __construct(){

    }
}

function generateCrfTokenSession(): array
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    try {
        $csrf_token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrf_token;
        return ['OK'];
    } catch (Exception $e) {
        return gestionErreur($e,'generateCrf');
    }
}

function verifyCrfToken($submittedToken): bool
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $submittedToken)) {
        return true;
    } else {
        return false;
    }
}



function messageErreurSession($message)
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['messageErreur'] = $message;
}

function getMessageErreurSession()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if(isset($_SESSION['messageErreur'])){
        return $_SESSION['messageErreur'];
    }

    return '';
}

function formErreurSession($formErreur)
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['formErreur'] = $formErreur;
}

function getFormErreurSession()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if(isset($_SESSION['formErreur'])){
        return $_SESSION['formErreur'];
    }

    return [];
}

function messageSucessSession($message)
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['messageSucess'] = $message;
}

function getMessageSucessSession()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if(isset($_SESSION['messageSucess'])){
        return $_SESSION['messageSucess'];
    }

    return '';
}

function postSession($post)
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['post'] = $post;
}

function getPostSession()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if(isset($_SESSION['post'])){
        return $_SESSION['post'];
    }

    return [];
}

function extractIdFromUrl($url,$string) {
    $segments = explode('/', $url);
    $editKey = array_search($string, $segments);

    if ($editKey !== false && isset($segments[$editKey + 1])) {
        return $segments[$editKey + 1];
    } else {
        return '';
    }
}

function securisePathId(History $history) {
    $urlSegments = explode('/', $history->getCurrPath());
    if (!isset($urlSegments[3])) {
        messageErreurSession('Route non trouvée');
        $history->redirection();
    }

    $oldUrlSegments = explode('/', $history->getOldPath());
    if (isset($oldUrlSegments[3])) {
        $oldId = extractIdFromUrl($history->getOldPath(), $oldUrlSegments[2]);
        $newId = extractIdFromUrl($history->getCurrPath(), $urlSegments[2]);
        if($oldId != $newId || $oldUrlSegments[2] != $urlSegments[2]){
            $history->redirection(implode('/',$oldUrlSegments));
        }
    }
}

