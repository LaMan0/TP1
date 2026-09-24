<?php
require __DIR__ . '/session.php';

// Pas de login en session : on n'affiche pas la page, même si l'URL a été tapée directement.
// header() doit partir avant tout HTML ; session.php ne produit aucune sortie, le redirect reste possible.
if (!isset($_SESSION['login'])) {
    header('Location: connexion.php');
    exit; // Sans exit, PHP continuerait : contrôle du rôle, puis le HTML de la page protégée.
}

// NE PAS MODIFIER — contrôle du rôle administrateur.
if (($_SESSION['role'] ?? '') !== 'admin') {
    http_response_code(403);
    exit('Accès réservé à l’administrateur.');
}
