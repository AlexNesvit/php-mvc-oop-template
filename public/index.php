<?php 
//Demarrer la session
session_start();

// Charger les confogurations
require_once '../config/config.php';

// Définir la langue (par défault français)
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

$lang = $_SESSION['lang'] ?? 'fr';

// Charger les traductions
$translations = include "../lang/{$lang}.php";

// Autoloadernpour charger les classes automatiquement
spl_autoload_register(function ($class) {
    require_once "../app/core/{$class}.php";
});

// Initialiser l'application
$app = new App();

