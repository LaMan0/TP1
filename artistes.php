<?php
require __DIR__ . '/includes/session.php';

// __DIR__ pointe sur le dossier de ce fichier : le require reste bon quel que soit le répertoire courant d'Apache.
require __DIR__ . '/includes/bdd.php';

// Requête fixe, aucune saisie utilisateur : query() suffit. prepare() sert quand une valeur vient de l'extérieur.
$requete = $pdo->query('SELECT id, nom, genre, pays FROM artiste ORDER BY nom');
// fetchAll() charge toute la liste. Dans la boucle, $artiste est une seule ligne de cette liste.
$artistes = $requete->fetchAll();

require __DIR__ . '/includes/header.php';
?>
<h1>Les artistes</h1>
<?php // count() plutôt qu'un 22 écrit en dur : le total reste juste après un ajout en administration. ?>
<p><?= count($artistes) ?> artistes programmés</p>
<table>
    <caption>Les artistes du festival, par ordre alphabétique</caption>
    <thead>
        <tr>
            <th scope="col">Nom</th>
            <th scope="col">Genre</th>
            <th scope="col">Pays</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($artistes as $artiste): ?>
        <tr>
            <?php // (int) sur l'id : l'URL ne peut contenir qu'un entier. htmlspecialchars : le nom est du texte, pas du HTML. ?>
            <td><a href="artiste.php?id=<?= (int) $artiste['id'] ?>"><?= htmlspecialchars($artiste['nom']) ?></a></td>
            <td><?= htmlspecialchars($artiste['genre'] ?: 'Non renseigné') ?></td>
            <td><?= htmlspecialchars($artiste['pays'] ?: 'Non renseigné') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php require __DIR__ . '/includes/footer.php'; ?>
