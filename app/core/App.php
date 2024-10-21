<?php 
// Classe principale de l'aplication

class App
{
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        // Déterminé quelle contrôleur utiliser
        if (file_exists("../app/controllers/{$url[0]}Controller.php")) {
            $this->controller = "{$url[0]}Controller";
            unset($url[0]);
        }

        require_once "../app/controllers/{$this->controller}.php";
        $this->controller = new $this->controller;

        // Déterminer quelle méthode du contrôleur utiliser
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }
        //Paramètres
        $this->params = $url ? array_values($url) : [];

        // Appel du contrôleur et de la méthode 
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    // Fonction pour analyser l'URL
    public function parseUrl()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return ['Home'];
    }
}