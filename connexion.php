<?php
require __DIR__ . '/includes/session.php';

if (isset($_SESSION['login'])) {
    header('Location: admin.php');
    exit;
}

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim((string) ($_POST['login'] ?? ''));
    $motDePasse = (string) ($_POST['mot_de_passe'] ?? '');
    $jeton = (string) ($_POST['csrf'] ?? '');

    if (!hash_equals($_SESSION['csrf'], $jeton)) {
        $erreur = 'Formulaire expiré. Réessayez.';
    } else {
        require __DIR__ . '/includes/bdd.php';

        $requete = $pdo->prepare(
            'SELECT id, login, mot_de_passe, role FROM utilisateur WHERE login = :login'
        );
        $requete->execute(['login' => $login]);
        $utilisateur = $requete->fetch();

        if ($utilisateur && password_verify($motDePasse, $utilisateur['mot_de_passe'])) {
            session_regenerate_id(true);
            $_SESSION['login'] = $utilisateur['login'];
            $_SESSION['role'] = $utilisateur['role'];
            $_SESSION['csrf'] = bin2hex(random_bytes(32));

            header('Location: admin.php');
            exit;
        }

        $erreur = 'Identifiants incorrects.';
    }
}

require __DIR__ . '/includes/header.php';
?>
<h1>Connexion à la gestion des artistes</h1>
<?php if ($erreur !== ''): ?>
<p role="alert"><?= htmlspecialchars($erreur) ?></p>
<?php endif; ?>
<form method="post" action="connexion.php">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
    <label for="login">Identifiant</label>
    <input id="login" name="login" required autocomplete="username">
    <label for="mot_de_passe">Mot de passe</label>
    <input id="mot_de_passe" name="mot_de_passe" type="password" required autocomplete="current-password">
    <button type="submit">Se connecter</button>
</form>
<?php require __DIR__ . '/includes/footer.php'; ?>
