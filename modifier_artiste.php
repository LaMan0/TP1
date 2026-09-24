<?php
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/bdd.php';

$erreur = '';
$id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
$artiste = false;

if ($id <= 0) {
    $erreur = 'Identifiant absent ou invalide.';
} else {
    // Même raison que la fiche publique : l'id vient de la requête HTTP, il ne doit pas être collé dans le SQL.
    $requete = $pdo->prepare('SELECT id, nom, genre, pays FROM artiste WHERE id = :id');
    $requete->execute(['id' => $id]);
    $artiste = $requete->fetch();

    if ($artiste === false) {
        $erreur = 'Artiste introuvable.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $artiste !== false) {
    $nom = trim((string) ($_POST['nom'] ?? ''));
    $genre = trim((string) ($_POST['genre'] ?? ''));
    $pays = trim((string) ($_POST['pays'] ?? ''));
    $jeton = (string) ($_POST['csrf'] ?? '');

    // En cas d'erreur, le formulaire réaffiche ce qui a été saisi, pas l'ancienne fiche.
    $artiste['nom'] = $nom;
    $artiste['genre'] = $genre;
    $artiste['pays'] = $pays;

    if (!hash_equals($_SESSION['csrf'], $jeton)) {
        $erreur = 'Formulaire expiré. Rechargez la page.';
    } elseif ($nom === '') {
        $erreur = 'Le nom est obligatoire.';
    } elseif (mb_strlen($nom) > 120) {
        $erreur = 'Le nom est limité à 120 caractères.';
    } elseif (mb_strlen($genre) > 60 || mb_strlen($pays) > 60) {
        $erreur = 'Le genre et le pays sont limités à 60 caractères.';
    } else {
        $requete = $pdo->prepare(
            'UPDATE artiste SET nom = :nom, genre = :genre, pays = :pays WHERE id = :id'
        );
        $requete->execute([
            'id' => $id,
            'nom' => $nom,
            'genre' => $genre,
            'pays' => $pays,
        ]);

        header('Location: admin.php');
        exit;
    }
}

require __DIR__ . '/includes/header.php';
?>
<h1>Modifier un artiste</h1>
<?php if ($erreur !== '' && $artiste === false): ?>
<p role="alert"><?= htmlspecialchars($erreur) ?></p>
<?php elseif ($artiste !== false): ?>
<?php if ($erreur !== ''): ?>
<p role="alert"><?= htmlspecialchars($erreur) ?></p>
<?php endif; ?>
<form method="post" action="modifier_artiste.php?id=<?= (int) $artiste['id'] ?>">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
    <input type="hidden" name="id" value="<?= (int) $artiste['id'] ?>">
    <label for="nom">Nom (obligatoire)</label>
    <input id="nom" name="nom" maxlength="120" required value="<?= htmlspecialchars($artiste['nom']) ?>">
    <label for="genre">Genre</label>
    <input id="genre" name="genre" maxlength="60" value="<?= htmlspecialchars((string) $artiste['genre']) ?>">
    <label for="pays">Pays</label>
    <input id="pays" name="pays" maxlength="60" value="<?= htmlspecialchars((string) $artiste['pays']) ?>">
    <button type="submit">Enregistrer les modifications</button>
</form>
<?php endif; ?>
<p><a href="admin.php">Retour à la gestion</a></p>
<?php require __DIR__ . '/includes/footer.php'; ?>
