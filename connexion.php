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

    // Le jeton du formulaire doit être celui de la session. hash_equals évite une comparaison qui fuit le temps de calcul.
    if (!hash_equals($_SESSION['csrf'], $jeton)) {
        $erreur = 'Formulaire expiré. Réessayez.';
    } else {
        require __DIR__ . '/includes/bdd.php';

        // :login n'est pas concaténé au SQL : un login du genre ' OR 1=1 -- ne change pas la requête.
        $requete = $pdo->prepare(
            'SELECT id, login, mot_de_passe, role FROM utilisateur WHERE login = :login'
        );
        $requete->execute(['login' => $login]);
        // false si ce login n'existe pas. On ne le dit pas à l'écran : même message que pour un mauvais mot de passe.
        $utilisateur = $requete->fetch();

        // Vérification en PHP, jamais dans un WHERE : password_hash() change de sel à chaque appel, l'empreinte n'est pas recalculable.
        if ($utilisateur && password_verify($motDePasse, $utilisateur['mot_de_passe'])) {
            // Nouveau id de session après login : un identifiant fixé avant la connexion ne sert plus.
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
