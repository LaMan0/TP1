<?php

// Avant tout HTML : session_start() envoie le cookie de session dans les en-têtes HTTP.
session_start();

// Un seul jeton pour la session, relu par les formulaires qui modifient quelque chose (connexion, ajout, déconnexion).
if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
