<?php $pageTitle = 'Ajouter un utilisateur'; ?>
<?php ob_start(); ?>

<h1>Ajouter un utilisateur</h1>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form method="POST" class="form-standard">
    <div class="form-group">
        <label for="username">Nom d'utilisateur *</label>
        <input type="text" id="username" name="username" required>
    </div>
    <div class="form-group">
        <label for="password">Mot de passe *</label>
        <input type="password" id="password" name="password" required>
    </div>
    <div class="form-group">
        <label for="role">Rôle *</label>
        <select id="role" name="role" required>
            <option value="">-- Sélectionner --</option>
            <option value="ADMIN">Administrateur</option>
            <option value="PHARMACIEN">Pharmacien</option>
            <option value="PREPARATEUR">Préparateur</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Créer</button>
    <a href="index.php?page=users" class="btn">Annuler</a>
</form>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/layout.php'; ?>
