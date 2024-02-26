<?php
include_once 'src/Model/Utilisateur.php';
include_once 'src/Service/Security.php';
include_once 'src/Repository/ContactRepository.php';


function getAllUtilisateurs() {
    $repoContact = new ContactRepository();
    $resultat = $repoContact->getAll();

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
    $repoContact = new ContactRepository();
    $repoContact->add($contact);

    if(!empty($contact->getErrors())){
        $erreurForm = $contact->getErrors();
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
    $repoContact = new ContactRepository();
    $error = $repoContact->delete($id);
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
    global $path,$pathForm,$contact;
    $urlSegments = explode('/', $path);

    if (!isset($urlSegments[3])) {
        http_response_code(404);
        echo json_encode(['message' => 'Route non trouvée']);
        return;
    }

    $id = extractIdFromUrl($path, 'edit');
    $repoContact = new ContactRepository();
    $contact = $repoContact->getById($id);
    if(!is_object($contact) && isset($contact['error']) ){
        messageErreurSession($contact['error']);
        header('Location: http://applicloud/');
        exit();
    }

    if($contact->getId() == 0)
    {
        messageErreurSession('l\'utilisateur n\'existe pas');
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
    global $path,$contact;
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

    $repoContact = new ContactRepository();
    $contact = $repoContact->getById($id);
    if(!is_object($contact) && isset($contact['error'])){
        messageErreurSession($contact['error']);
        header('Location: http://applicloud/');
        exit();
    }

    $contact->setUtilisateur($_POST);
    $repoContact->put($contact);
    if(!empty($contact->getErrors())){
        $erreurForm = $contact->getErrors();
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
