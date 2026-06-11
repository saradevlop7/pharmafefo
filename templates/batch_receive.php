<?php $pageTitle = 'Réception de stock'; ?>
<?php ob_start(); ?>

<h1>Réception de stock</h1>

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
        <label for="batch_number">Numéro de lot *</label>
        <input type="text" id="batch_number" name="batch_number" required>
    </div>
    <div class="form-group">
        <label for="expiry_date">Date de péremption *</label>
        <input type="date" id="expiry_date" name="expiry_date" required>
    </div>
    <div class="form-group">
        <label for="quantity">Quantité *</label>
        <input type="number" id="quantity" name="quantity" min="1" required>
    </div>
    <button type="submit" class="btn btn-primary">Réceptionner</button>
</form>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/layout.php'; ?>
