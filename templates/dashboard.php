<?php $pageTitle = 'Tableau de bord'; ?>
<?php ob_start(); ?>

<h1>Tableau de bord</h1>
<p>Bienvenue, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>. Vous êtes connecté en tant que <strong><?= $_SESSION['role'] ?></strong>.</p>

<div class="dashboard-cards">
    <div class="card">
        <h3>Médicaments</h3>
        <p><a href="index.php?page=medications">Gérer les médicaments</a></p>
    </div>
    <div class="card">
        <h3>Lots</h3>
        <p><a href="index.php?page=batches">Voir les lots</a></p>
    </div>
    <div class="card">
        <h3>Réception de stock</h3>
        <p><a href="index.php?page=batch_receive">Réceptionner un lot</a></p>
    </div>
    <div class="card">
        <h3>Sortie de stock</h3>
        <p><a href="index.php?page=stock_out">Effectuer une sortie</a></p>
    </div>
    <div class="card">
        <h3>Alertes péremption</h3>
        <p><a href="index.php?page=alerts">Voir les alertes</a></p>
    </div>
    <?php if (in_array($_SESSION['role'], ['ADMIN', 'PHARMACIEN'])): ?>
    <div class="card">
        <h3>Rapport des pertes</h3>
        <p><a href="index.php?page=report_losses">Voir le rapport</a></p>
    </div>
    <?php endif; ?>
</div>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/layout.php'; ?>
