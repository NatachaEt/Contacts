<?php
include_once 'src/Model/Utilisateur.php';
include_once 'src/Service/Security.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

function getAllUtilisateurs() {
    $utilisateur = new Utilisateur();
    $resultat = $utilisateur->getAll();

    header('Content-Type: application/json');
    if (isset($resultat['error'])) {
        http_response_code(500);
        echo json_encode(['erreur' => $resultat['error']]);
    } else {
        http_response_code(200);
        echo json_encode($resultat);
    }
}

function addContact() {
    if (!isset($_POST['csrf_token']) || !verifyCrfToken($_POST['csrf_token'])){
        $messageErreur = 'Erreur jeton csrf veuillez recharger la page';
        messageErreurSession($messageErreur);
        header('Location: http://applicloud/utilisateur/create');
        exit();
    }

    $contact = new Utilisateur($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['telephone']);
    $error = $contact->add();
    if(isset($error['error'])){
        $erreurForm = $error['error'];
        formErreurSession($erreurForm);
        postSession($_POST);
        generateCrfTokenSession();
        header('Location: http://applicloud/utilisateur/create');
        exit();
    }

    messageSucessSession('Contact Ajouté');
    header('Location: http://applicloud/');
    exit();
}

function deleteContact() {
    global $path;
    $id = extractIdFromUrl($path, 'delete');
    $contact = new Utilisateur();
    $contact->setId($id);
    $error = $contact->delete();
    if(isset($error['error'])){
        messageErreurSession($error['error']);
        header('Location: http://applicloud/');
        exit();
    }

    messageSucessSession('Contact Supprimé');
    header('Location: http://applicloud/');
    exit();
}

function afficheEdit() {
    /** @var Utilisateur $contact */
    global $path,$pathForm,$contact;
    $urlSegments = explode('/', $path);

    if (!isset($urlSegments[3])) {
        http_response_code(404);
        echo json_encode(['message' => 'Route non trouvée']);
        return;
    }

    $id = extractIdFromUrl($path, 'edit');
    $contact = new Utilisateur();
    $contact->setId($id);
    $error = $contact->get();
    if(isset($error['error'])){
        messageErreurSession($error['error']);
        header('Location: http://applicloud/');
        exit();
    }
    if(!empty(getPostSession())){
        $contact->setUtilisateur(getPostSession());
    }

    $error = generateCrfTokenSession();
    if(isset($error['error'])){
        messageErreurSession($error['error']);
    }

    $pathForm = "/utilisateur/edit/$id";
    include_once 'template/contacts/form.php';
}

function postEdit() {
    /** @var Utilisateur $contact */
    global $path,$pathForm,$contact;
    $urlSegments = explode('/', $path);
    if (!isset($urlSegments[3])) {
        http_response_code(404);
        echo json_encode(['message' => 'Route non trouvée']);
        return;
    }

    $id = extractIdFromUrl($path, 'edit');
    if (!isset($_POST['csrf_token']) || !verifyCrfToken($_POST['csrf_token'])){
        $messageErreur = 'Erreur jeton csrf veuillez recharger la page';
        messageErreurSession($messageErreur);
        header('Location: http://applicloud/utilisateur/edit/'.$id);
        exit();
    }

    $contact = new Utilisateur();
    $contact->setId($id);
    $error = $contact->get();
    if(isset($error['error'])){
        messageErreurSession($error['error']);
        header('Location: http://applicloud/');
        exit();
    }

    $contact->setUtilisateur($_POST);
    $error = $contact->put();
    if(isset($error['error'])){
        $erreurForm = $error['error'];
        formErreurSession($erreurForm);
        postSession($_POST);
        generateCrfTokenSession();
        header('Location: http://applicloud/utilisateur/edit/'.$id);
        exit();
    }

    messageSucessSession('Contact Modifié');
    header('Location: http://applicloud/');
    exit();
}