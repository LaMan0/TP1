<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Le festival</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header>
    <p class="marque">Le festival · 9 au 11 juillet 2027</p>
    <nav aria-label="Navigation principale">
        <a href="index.php">Accueil</a>
        <a href="artistes.php">Artistes</a>
        <a href="admin.php">Administration</a>
        <?php if (isset($_SESSION['login'])): ?>
        <form method="post" action="deconnexion.php" class="deconnexion">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
            <button type="submit">Se déconnecter</button>
        </form>
        <?php endif; ?>
    </nav>
</header>
<main>
