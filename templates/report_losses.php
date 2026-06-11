<?php $pageTitle = 'Rapport des pertes'; ?>
<?php ob_start(); ?>

<h1>Rapport des pertes financières</h1>
<p class="info-text">Ce rapport affiche les pertes dues aux lots expirés.</p>

<table class="data-table">
    <thead>
        <tr>
            <th>Numéro de lot</th>
            <th>Médicament</th>
            <th>Date de péremption</th>
            <th>Quantité perdue</th>
            <th>Prix unitaire (€)</th>
            <th>Perte (€)</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($reportLines)): ?>
            <tr><td colspan="6">Aucune perte enregistrée.</td></tr>
        <?php else: ?>
            <?php foreach ($reportLines as $line): ?>
            <tr>
                <td><?= htmlspecialchars($line['batch']->getBatchNumber()) ?></td>
                <td><?= htmlspecialchars($line['batch']->getMedicationName()) ?></td>
                <td><?= date('d/m/Y', strtotime($line['batch']->getExpiryDate())) ?></td>
                <td><?= $line['lost_quantity'] ?></td>
                <td><?= number_format($line['unit_price'], 2, ',', ' ') ?></td>
                <td class="loss-amount"><?= number_format($line['loss'], 2, ',', ' ') ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5"><strong>Total des pertes</strong></td>
            <td class="loss-amount"><strong><?= number_format($totalLoss, 2, ',', ' ') ?> €</strong></td>
        </tr>
    </tfoot>
</table>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/layout.php'; ?>
