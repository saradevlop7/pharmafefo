<?php $pageTitle = 'Alertes de péremption'; ?>
<?php ob_start(); ?>

<?php
$filterCritical = $filterCritical ?? false;
?>

<h1>Alertes de péremption</h1>

<div class="filter-bar">
    <a href="index.php?page=alerts" class="btn <?= !$filterCritical ? 'btn-primary' : '' ?>">Tous les lots</a>
    <a href="index.php?page=alerts&filter=critical" class="btn <?= $filterCritical ? 'btn-danger' : '' ?>">Lots critiques (Rouge)</a>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Numéro de lot</th>
            <th>Médicament</th>
            <th>Date de péremption</th>
            <th>Jours restants</th>
            <th>Quantité</th>
            <th>Niveau</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $today = new DateTime();
        $hasRows = false;
        $batches = $batches ?? [];
        foreach ($batches as $batch):
            if ($batch->getStatus() === BatchStatus::EXPIRED) continue;

            $expiryDate = new DateTime($batch->getExpiryDate());
            $diff = $today->diff($expiryDate);
            $daysRemaining = $expiryDate > $today ? (int) $diff->days : -(int) $diff->days;

          
if ($daysRemaining < 30) {
    $level = 'red';
    $levelLabel = 'Rouge (< 30 jours)';
} elseif ($daysRemaining < 90) {
    $level = 'orange';
    $levelLabel = 'Orange (< 90 jours)';
} elseif ($daysRemaining > 180) {
    $level = 'green';
    $levelLabel = 'Vert (> 6 mois)';
} else {
    $level = 'orange';
    $levelLabel = 'Orange';
}

          
            if ($filterCritical && $level !== 'red') continue;
            $hasRows = true;
        ?>
        <tr class="alert-row alert-<?= $level ?>">
            <td><?= htmlspecialchars($batch->getBatchNumber()) ?></td>
            <td><?= htmlspecialchars($batch->getMedicationName()) ?></td>
            <td><?= date('d/m/Y', strtotime($batch->getExpiryDate())) ?></td>
            <td><?= $daysRemaining ?> jour(s)</td>
            <td><?= $batch->getQuantity() ?></td>
            <td><span class="badge badge-alert-<?= $level ?>"><?= $levelLabel ?></span></td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$hasRows): ?>
            <tr><td colspan="6">Aucun lot à afficher.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/layout.php'; ?>
