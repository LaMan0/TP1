<?php

// $pdo est la connexion partagée : chaque page qui inclut ce fichier récupère la même variable.
// root et le mot de passe vide sont le compte MySQL de XAMPP, pas le compte du site (admin / Festival2027!).
$pdo = new PDO(
    // utf8mb4 aligne PHP sur le jeu de caractères de festival.sql (accents, « Électro », etc.).
    'mysql:host=localhost;dbname=festival;charset=utf8mb4',
    'root',
    '',
    [
        // Une erreur SQL lève une PDOException (fichier + ligne) au lieu d'échouer sans message clair.
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // fetch() / fetchAll() renvoient des tableaux associatifs : $artiste['nom'], pas un index numérique.
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
);
