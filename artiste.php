<?php
require __DIR__ . '/includes/session.php';

$id = (int) ($_GET['id'] ?? 0);
$artiste = false;
$message = '';

if ($id <= 0) {
    $message = 'Identifiant absent ou invalide.';
} else {
    require __DIR__ . '/includes/bdd.php';

    // L'id arrive par l'URL : prepare() + execute(), contrairement à la liste publique dont le SQL est fixe.
    $requete = $pdo->prepare('SELECT id, nom, genre, pays FROM artiste WHERE id = :id');
    $requete->execute(['id' => $id]);
    // Une ligne, ou false. fetchAll() n'aurait pas de sens pour une fiche.
    $artiste = $requete->fetch();

    if ($artiste === false) {
        $message = 'Artiste introuvable.';
    }
}

require __DIR__ . '/includes/header.php';
?>
<?php if ($message !== ''): ?>
    <h1>Fiche artiste</h1>
    <p><?= htmlspecialchars($message) ?></p>
<?php else: ?>
    <h1><?= htmlspecialchars($artiste['nom']) ?></h1>
    <dl>
        <dt>Genre</dt><dd><?= htmlspecialchars($artiste['genre'] ?: 'Non renseigné') ?></dd>
        <dt>Pays</dt><dd><?= htmlspecialchars($artiste['pays'] ?: 'Non renseigné') ?></dd>
    </dl>
<?php endif; ?>
<p><a href="artistes.php">Retour à la liste des artistes</a></p>
<?php require __DIR__ . '/includes/footer.php'; ?>
