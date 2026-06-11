<?php $pageTitle = 'Utilisateurs'; ?>
<?php ob_start(); ?>

<h1>Gestion des utilisateurs</h1>

<a href="index.php?page=user_create" class="btn btn-primary">Ajouter un utilisateur</a>

<table class="data-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom d'utilisateur</th>
            <th>Rôle</th>
            <th>Date de création</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user->getId() ?></td>
            <td><?= htmlspecialchars($user->getUsername()) ?></td>
            <td><?= $user->getRole() ?></td>
            <td><?= date('d/m/Y H:i', strtotime($user->getCreatedAt())) ?></td>
            <td>
                <?php if ($user->getId() !== (int) $_SESSION['user_id']): ?>
                    <a href="index.php?page=user_delete&id=<?= $user->getId() ?>" class="btn btn-small btn-danger" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
                <?php else: ?>
                    <em>(vous)</em>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/layout.php'; ?>
