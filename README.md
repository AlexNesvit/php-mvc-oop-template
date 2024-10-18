# 🟣 Interview Prep PHP + Laravel 🟣 
**[Описание на русском языке](README_ru.md)**

Plateforme de préparation aux entretiens PHP.

## Description
## Étapes de développement et explications

### 1. Création de la structure du projet

Nous avons commencé par organiser la structure du projet selon le modèle MVC (Modèle-Vue-Contrôleur). Cela permet de séparer la logique de l'application en trois composants distincts :

- **Modèle (Model) :** Responsable de la gestion des données et de l'interaction avec la base de données.
- **Vue (View) :** S'occupe de l'affichage des données à l'utilisateur.
- **Contrôleur (Controller) :** Gère la logique métier et la coordination entre le modèle et la vue.

#### Structure du projet :

/interview_prep
├── /app
│   ├── /controllers    Contient les contrôleurs
│   ├── /models         Contient les modèles de données
│   ├── /core           Contient les classes principales de l’application
├── /public
│   └── index.php       Point d’entrée principal de l’application
├── /config
│   └── config.php      Fichiers de configuration (connexion à la base de données)
├── /views              Contient les vues (templates HTML)
├── /lang               Contient les fichiers de traduction pour chaque langue
├── /assets
│   ├── /css            Feuilles de style CSS
│   ├── /js             Scripts JavaScript
│   └── /images         Images utilisées dans le site

### 2. Connexion à la base de données

Nous avons utilisé PHP et PDO pour établir une connexion sécurisée avec la base de données MySQL. Le fichier de configuration `config.php` contient les informations nécessaires pour se connecter à la base de données.

#### Étapes pour la création de la base de données :

1. Ouvrir **PHPMyAdmin** via l'interface MAMP.
2. Créer une base de données sous le nom `interview_prep`.
3. Créer les tables nécessaires à l'application (utilisateurs, questions, leçons, etc.).

#### Code de connexion à la base de données :

```php
<?php
// Informations de connexion à la base de données
$db_host = 'localhost';
$db_name = 'interview_prep';
$db_user = 'root';
$db_pass = 'root';

// Connexion à la base de données
try {
    $db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

Explication du code :

	•	$db_host, $db_name, $db_user, $db_pass : Ces variables contiennent les informations nécessaires à la connexion (nom d’hôte, nom de la base, utilisateur et mot de passe).
	•	new PDO(…): Cette ligne crée une nouvelle connexion à la base de données en utilisant l’extension PDO.
	•	setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION) : Cela permet d’activer la gestion des erreurs sous forme d’exceptions.
	•	catch(PDOException $e) : Si une erreur se produit lors de la connexion, elle est capturée et affichée.

3. Explication des composants principaux

Fichier index.php (point d’entrée)

Le fichier public/index.php est le point d’entrée de l’application. Il gère les demandes des utilisateurs et dirige l’application vers le bon contrôleur.

<?php
// Démarrer la session
session_start();

// Charger les configurations
require_once '../config/config.php';

// Définir la langue (par défaut français)
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

$lang = $_SESSION['lang'] ?? 'fr';

// Charger les traductions
$translations = include "../lang/{$lang}.php";

// Autoloader pour charger les classes automatiquement
spl_autoload_register(function ($class) {
    require_once "../app/core/{$class}.php";
});

// Initialiser l'application
$app = new App();

Explication du code :

	•	session_start() : Démarre une session PHP pour stocker des informations utilisateur, comme la langue choisie.
	•	require_once ‘../config/config.php’ : Charge les configurations du projet, notamment la connexion à la base de données.
	•	$_SESSION[‘lang’] : Cette variable stocke la langue sélectionnée par l’utilisateur.
	•	spl_autoload_register() : Charge automatiquement les classes nécessaires à l’exécution de l’application.
	•	$app = new App() : Initialise l’application en appelant la classe App qui va gérer le routage.

4. Gestion du routage avec App.php

Le fichier App.php dans le dossier core est responsable de la gestion du routage. Il analyse l’URL et dirige l’utilisateur vers le bon contrôleur et la bonne méthode.

<?php
// Classe principale de l'application

class App
{
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        // Détermine quel contrôleur utiliser
        if (file_exists("../app/controllers/{$url[0]}Controller.php")) {
            $this->controller = "{$url[0]}Controller";
            unset($url[0]);
        }

        require_once "../app/controllers/{$this->controller}.php";
        $this->controller = new $this->controller;

        // Détermine quelle méthode du contrôleur utiliser
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Paramètres
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
Explication du code :

	•	$controller, $method, $params : Ces variables contiennent respectivement le contrôleur, la méthode et les paramètres extraits de l’URL.
	•	parseUrl() : Analyse l’URL pour déterminer le contrôleur et la méthode à utiliser.
	•	call_user_func_array : Appelle dynamiquement la méthode du contrôleur avec les paramètres fournis dans l’URL.

Prochaines étapes

	1.	Création des modèles :
Nous allons créer les modèles pour interagir avec la base de données.
	2.	Développement des contrôleurs :
Les contrôleurs géreront la logique métier et la communication entre les vues et les modèles.
	3.	Implémentation de la fonctionnalité multilingue complète :
La plateforme permettra de basculer entre le français et le russe via une interface utilisateur.

Auteur

	•	Alex NESVIT — Développeur du projet.

Licence:

Ce projet est sous licence MIT.