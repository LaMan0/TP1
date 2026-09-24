<?php
require __DIR__ . '/includes/session.php';


/ Acompléter TODO 1.1 

$artistes = []; / Acompléter  TODO 1.2

require __DIR__ . '/includes/header.php';
?>
<h1>Les artistes</h1>
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
    <?php foreach ([] as $artiste): // TODO 1.3 ?>
        <tr>
            <td><a href="artiste.php?id=<?= (int) $artiste['id'] ?>"><?= htmlspecialchars($artiste['nom']) ?></a></td>
            <td><?= htmlspecialchars($artiste['genre'] ?: 'Non renseigné') ?></td>
            <td><?= htmlspecialchars($artiste['pays'] ?: 'Non renseigné') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php require __DIR__ . '/includes/footer.php'; ?>
