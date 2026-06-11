<?php $pageTitle = 'Lots'; ?>
<?php ob_start(); ?>

<h1>Liste des lots</h1>

<table class="data-table">
    <thead>
        <tr>
            <th>Numéro de lot</th>
            <th>Médicament</th>
            <th>Date de péremption</th>
            <th>Quantité</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($batches)): ?>
            <tr><td colspan="6">Aucun lot enregistré.</td></tr>
        <?php else: ?>
            <?php foreach ($batches as $batch): ?>
            <tr>
                <td><?= htmlspecialchars($batch->getBatchNumber()) ?></td>
                <td><?= htmlspecialchars($batch->getMedicationName()) ?></td>
                <td><?= date('d/m/Y', strtotime($batch->getExpiryDate())) ?></td>
                <td><?= $batch->getQuantity() ?></td>
                <td>
                    <span class="badge badge-<?= strtolower($batch->getStatus()->value) ?>">
                        <?= $batch->getStatus()->value ?>
                    </span>
                </td>
                <td>
                    <?php if ($batch->getStatus() !== BatchStatus::EXPIRED && in_array($_SESSION['role'], ['ADMIN', 'PHARMACIEN'])): ?>
                        <a href="index.php?page=batch_expire&id=<?= $batch->getId() ?>" class="btn btn-small btn-danger" onclick="return confirm('Déclarer ce lot comme expiré ?')">Déclarer expiré</a>
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
