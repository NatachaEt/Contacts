<?php
include_once 'src/Controller/UtilisateurController.php';
include_once 'src/Service/validateData.php';
include_once 'src/Service/Security.php';

$contact = new Utilisateur();
$pathForm = '';

function route($method, $path)
{
    $history = History::getInstance();
    $history->newPath($path);
    $contact = new Utilisateur();

    if ($method === 'GET') {
        if(strpos($path, '/utilisateur/delete/') === 0) {
            securisePathId($history);
            deleteContact();
            return;
        }

        if(strpos($path, '/utilisateur/edit/') === 0) {
            securisePathId($history);
            afficheEdit();
            return;
        }

        switch ($path) {
            case '/utilisateurs':
                getAllUtilisateurs();
                break;
            case '/utilisateur/delete':
                if (!isset($urlSegments[3])) {
                    http_response_code(404);
                    echo json_encode(['message' => 'Route non trouvée']);
                }
                deleteContact();
                break;
            case '/utilisateur/create':
                global $contact;
                $error = generateCrfTokenSession();
                if(isset($error['error'])){
                    messageErreurSession($error['error']);
                }
                $pathForm = "/utilisateur/create";

                if(!empty(getPostSession())){
                    $contact->setUtilisateur(getPostSession());
                }

                include 'template/contacts/form.php';
                break;
            case '/':
                include 'template/contacts/index.php';
                break;
            default:
                http_response_code(404);
                echo json_encode(['message' => 'Route non trouvée']);
                break;
        }
    } elseif ($method === 'POST'){
        if(strpos($path, '/utilisateur/edit/') === 0) {
            securisePathId($history);
            postEdit();
            return;
        }

        switch ($path) {
            case '/utilisateur/create':
                addContact();
                break;
            default :
                http_response_code(404);
                echo json_encode(['message' => 'Route non trouvée']);
                break;
        }
    } else {
        http_response_code(405);
        echo json_encode(['message' => 'Méthode non autorisée']);
    }
}
