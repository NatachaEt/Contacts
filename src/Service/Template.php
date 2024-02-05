<?php
include_once 'src/Service/Security.php';

class Template {

    public string $messageErreur;
    public string $messageSucess;
    public array $erreurForm;


    public function __construct() {
        $this->messageSucess = getMessageSucessSession();
        $this->messageErreur = getMessageErreurSession();
        $this->erreurForm = getFormErreurSession();
        $this->resetSession();
    }

    public function affichageMessage() {
        if (!empty($this->messageErreur)) {
            echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4  my-4 w-11/12" role="alert">';
            echo '<strong>Erreur :</strong> ' . $this->messageErreur;
            echo '</div>';
        }

        if (!empty($this->messageSucess)) {
            echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4  my-4 w-11/12" role="alert">';
            echo '<strong>Succès :</strong> ' . $this->messageSucess;
            echo '</div>';
        }
    }

    public function affichageErreurForm() {
        if (!empty($this->erreurForm)) {
            echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">';
            echo '<strong>Erreurs dans le formulaire :</strong>';
            echo '<ul class="list-disc pl-4">';
            foreach ($this->erreurForm as $erreur) {
                echo '<li>' . $erreur . '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }
    }

    public function resetSession() {
        messageErreurSession('');
        messageSucessSession('');
        formErreurSession([]);
        postSession([]);
    }

}
