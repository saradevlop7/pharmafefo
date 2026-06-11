<?php $pageTitle = isset($medication) && $medication->getId() ? 'Modifier médicament' : 'Ajouter médicament'; ?>
<?php ob_start(); ?>

<h1><?= $pageTitle ?></h1>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form method="POST" class="form-standard">
    <div class="form-group">
        <label for="name">Nom du médicament *</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($medication->getName() ?? '') ?>" required>
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description"><?= htmlspecialchars($medication->getDescription() ?? '') ?></textarea>
    </div>
    <div class="form-group">
        <label for="unit_price">Prix unitaire (€) *</label>
        <input type="number" id="unit_price" name="unit_price" step="0.01" min="0" value="<?= $medication->getUnitPrice() ?? 0 ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a href="index.php?page=medications" class="btn">Annuler</a>
</form>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/layout.php'; ?>
