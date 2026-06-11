<?php $pageTitle = 'Sortie de stock'; ?>
<?php ob_start(); ?>

<h1>Sortie de stock (FEFO)</h1>
<p class="info-text">La règle FEFO est appliquée automatiquement : le lot avec la date de péremption la plus proche est sélectionné en priorité.</p>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form method="POST" class="form-standard">
    <div class="form-group">
        <label for="medication_id">Médicament *</label>
        <select id="medication_id" name="medication_id" required>
            <option value="">-- Sélectionner --</option>
            <?php foreach ($medications as $med): ?>
                <option value="<?= $med->getId() ?>"><?= htmlspecialchars($med->getName()) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="quantity">Quantité à sortir *</label>
        <input type="number" id="quantity" name="quantity" min="1" required>
    </div>
    <button type="submit" class="btn btn-primary">Effectuer la sortie</button>
</form>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/layout.php'; ?>
