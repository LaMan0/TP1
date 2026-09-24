<?php
require __DIR__ . '/session.php';

/ A compléter TODO 2.1 — Remplacer ces deux lignes par le contrôle de connexion.
http_response_code(503);
exit('TODO 2.1 à compléter.');

// NE PAS MODIFIER — contrôle du rôle administrateur.
if (($_SESSION['role'] ?? '') !== 'admin') {
    http_response_code(403);
    exit('Accès réservé à l’administrateur.');
}
