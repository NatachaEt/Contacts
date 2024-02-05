<?php
include_once 'config/config.php';
class History
{

    private static $instance;
    private string $oldPath = '/';
    private string $currPath = '/';

    private function __construct() {
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getOldPath() {
        return $this->oldPath;
    }

    public function getCurrPath() {
        return$this->currPath;
    }

    public function newPath(string $newPath){
        $this->oldPath = $this->currPath;
        $this->currPath = $newPath;
    }

    function redirection(string $path = '') {
        $path = CONFIG['defaultURL']. $path;
        header('Location: '.$path);
        exit();
    }
}