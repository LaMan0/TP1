<?php
require __DIR__ . '/includes/auth.php';

$erreur = '';
$nom = '';
$genre = '';
$pays = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim((string) ($_POST['nom'] ?? ''));
    $genre = trim((string) ($_POST['genre'] ?? ''));
    $pays = trim((string) ($_POST['pays'] ?? ''));
    $jeton = (string) ($_POST['csrf'] ?? '');

    if (!hash_equals($_SESSION['csrf'], $jeton)) {
        $erreur = 'Formulaire expiré. Rechargez la page.';
    } elseif ($nom === '') {
        $erreur = 'Le nom est obligatoire.';
    } elseif (mb_strlen($nom) > 120) {
        // mb_strlen compte les caractères, pas les octets : un accent ne « mange » pas deux places.
        $erreur = 'Le nom est limité à 120 caractères.';
    } elseif (mb_strlen($genre) > 60 || mb_strlen($pays) > 60) {
        $erreur = 'Le genre et le pays sont limités à 60 caractères.';
    }

    // On n'écrit en base que si les trois étapes sont passées : lecture, contrôles, puis enregistrement.
    if ($erreur === '') {
        require __DIR__ . '/includes/bdd.php';

        // Les :nom restent des marqueurs. execute() transmet les valeurs à part : elles ne deviennent pas du SQL.
        $requete = $pdo->prepare(
            'INSERT INTO artiste (nom, genre, pays) VALUES (:nom, :genre, :pays)'
        );
        $requete->execute([
            'nom' => $nom,
            'genre' => $genre,
            'pays' => $pays,
        ]);

        // Post/Redirect/Get : le navigateur quitte cette page, un rechargement ne réinsère pas l'artiste.
        header('Location: admin.php');
        exit;
    }
}

require __DIR__ . '/includes/header.php';
?>
<h1>Ajouter un artiste</h1>
<?php if ($erreur !== ''): ?>
<p role="alert"><?= htmlspecialchars($erreur) ?></p>
<?php endif; ?>
<form method="post" action="ajout_artiste.php">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
    <label for="nom">Nom (obligatoire)</label>
    <input id="nom" name="nom" maxlength="120" required value="<?= htmlspecialchars($nom) ?>">
    <label for="genre">Genre</label>
    <input id="genre" name="genre" maxlength="60" value="<?= htmlspecialchars($genre) ?>">
    <label for="pays">Pays</label>
    <input id="pays" name="pays" maxlength="60" value="<?= htmlspecialchars($pays) ?>">
    <button type="submit">Enregistrer l’artiste</button>
</form>
<p><a href="admin.php">Retour à la gestion</a></p>
<?php require __DIR__ . '/includes/footer.php'; ?>
