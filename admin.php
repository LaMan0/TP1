<?php
// auth.php d'abord : sans login, redirection vers connexion.php ; sans rôle admin, 403. La requête SQL n'est pas exécutée.
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/bdd.php';

$requete = $pdo->query('SELECT id, nom, genre, pays FROM artiste ORDER BY nom');
$artistes = $requete->fetchAll();

require __DIR__ . '/includes/header.php';
?>
<h1>Gestion des artistes</h1>
<p>Connecté : <?= htmlspecialchars($_SESSION['login']) ?></p>
<p><a class="bouton" href="ajout_artiste.php">Ajouter un artiste</a></p>
<table>
    <caption>Les artistes du festival, par ordre alphabétique</caption>
    <thead>
        <tr>
            <th scope="col">Nom</th>
            <th scope="col">Genre</th>
            <th scope="col">Pays</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($artistes as $artiste): ?>
        <tr>
            <td><a href="artiste.php?id=<?= (int) $artiste['id'] ?>"><?= htmlspecialchars($artiste['nom']) ?></a></td>
            <td><?= htmlspecialchars($artiste['genre'] ?: 'Non renseigné') ?></td>
            <td><?= htmlspecialchars($artiste['pays'] ?: 'Non renseigné') ?></td>
            <td><a href="modifier_artiste.php?id=<?= (int) $artiste['id'] ?>">Modifier</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php require __DIR__ . '/includes/footer.php'; ?>
