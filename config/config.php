<?php
// Informations de connexion à la base de données
$db_host = 'localhost';
$db_name = 'php_laravel_proprep';
$db_user = 'root';
$db_pass = 'root';

// Connection à la base de données
    try {
        $db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo ('Connection à la base de données est ok' . '<br>');
    } catch (PDOException $e) {
        die('Erreur de connection : ' . $e->getMessage());
}