<?php $pageTitle = 'Médicaments'; ?>
<?php ob_start(); ?>

<h1>Liste des médicaments</h1>

<?php if (in_array($_SESSION['role'], ['ADMIN', 'PHARMACIEN'])): ?>
    <a href="index.php?page=medication_create" class="btn btn-primary">Ajouter un médicament</a>
<?php endif; ?>

<table class="data-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Description</th>
            <th>Prix unitaire (€)</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($medications)): ?>
            <tr><td colspan="5">Aucun médicament enregistré.</td></tr>
        <?php else: ?>
            <?php foreach ($medications as $medication): ?>
            <tr>
                <td><?= $medication->getId() ?></td>
                <td><?= htmlspecialchars($medication->getName()) ?></td>
                <td><?= htmlspecialchars($medication->getDescription() ?? '-') ?></td>
                <td><?= number_format($medication->getUnitPrice(), 2, ',', ' ') ?></td>
                <td>
                    <?php if (in_array($_SESSION['role'], ['ADMIN', 'PHARMACIEN'])): ?>
                        <a href="index.php?page=medication_edit&id=<?= $medication->getId() ?>" class="btn btn-small">Modifier</a>
                        <a href="index.php?page=medication_delete&id=<?= $medication->getId() ?>" class="btn btn-small btn-danger" onclick="return confirm('Supprimer ce médicament ?')">Supprimer</a>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/layout.php'; ?>
