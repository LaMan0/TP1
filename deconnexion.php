<?php
require __DIR__ . '/includes/session.php';

$jeton = (string) ($_POST['csrf'] ?? '');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hash_equals($_SESSION['csrf'], $jeton)) {
    http_response_code(403);
    exit('Déconnexion non autorisée.');
}

$_SESSION = [];
$cookie = session_get_cookie_params();
setcookie(
    session_name(),
    '',
    time() - 3600,
    $cookie['path'],
    $cookie['domain'],
    $cookie['secure'],
    $cookie['httponly']
);
session_destroy();

header('Location: index.php');
exit;
