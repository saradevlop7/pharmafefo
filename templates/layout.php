<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaFEFO - <?= $pageTitle ?? 'Accueil' ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php if (isset($_SESSION['user_id'])): ?>
    <nav class="navbar">
        <div class="nav-brand">PharmaFEFO</div>
        <ul class="nav-links">
            <li><a href="index.php?page=dashboard">Tableau de bord</a></li>
            <li><a href="index.php?page=medications">Médicaments</a></li>
            <li><a href="index.php?page=batches">Lots</a></li>
            <li><a href="index.php?page=batch_receive">Réception</a></li>
            <li><a href="index.php?page=stock_out">Sortie stock</a></li>
            <li><a href="index.php?page=alerts">Alertes</a></li>
            <?php if (in_array($_SESSION['role'], ['ADMIN', 'PHARMACIEN'])): ?>
            <li><a href="index.php?page=report_losses">Rapport pertes</a></li>
            <?php endif; ?>
            <?php if ($_SESSION['role'] === 'ADMIN'): ?>
            <li><a href="index.php?page=users">Utilisateurs</a></li>
            <?php endif; ?>
        </ul>
        <div class="nav-user">
            <span><?= htmlspecialchars($_SESSION['username']) ?> (<?= $_SESSION['role'] ?>)</span>
            <a href="index.php?page=logout" class="btn-logout">Déconnexion</a>
        </div>
    </nav>
    <?php endif; ?>

    <main class="container">
        <?= $content ?? '' ?>
    </main>
</body>
</html>
