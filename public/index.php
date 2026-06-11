<?php

session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Controller/AuthController.php';
require_once __DIR__ . '/../src/Controller/MedicationController.php';
require_once __DIR__ . '/../src/Controller/BatchController.php';
require_once __DIR__ . '/../src/Controller/StockController.php';
require_once __DIR__ . '/../src/Controller/ReportController.php';

$page = $_GET['page'] ?? 'login';

// Redirection si non connecté
if (!isset($_SESSION['user_id']) && $page !== 'login') {
    header('Location: index.php?page=login');
    exit;
}

// Redirection si déjà connecté et page login
if (isset($_SESSION['user_id']) && $page === 'login') {
    header('Location: index.php?page=dashboard');
    exit;
}

// Routeur simple
switch ($page) {
    case 'login':
        $controller = new AuthController();
        $controller->login();
        break;

    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'dashboard':
        require __DIR__ . '/../templates/dashboard.php';
        break;

    // Médicaments
    case 'medications':
        $controller = new MedicationController();
        $controller->list();
        break;

    case 'medication_create':
        $controller = new MedicationController();
        $controller->create();
        break;

    case 'medication_edit':
        $controller = new MedicationController();
        $controller->edit();
        break;

    case 'medication_delete':
        $controller = new MedicationController();
        $controller->delete();
        break;

    // Lots
    case 'batches':
        $controller = new BatchController();
        $controller->list();
        break;

    case 'batch_receive':
        $controller = new BatchController();
        $controller->receive();
        break;

    case 'batch_expire':
        $controller = new BatchController();
        $controller->expire();
        break;

    // Alertes
    case 'alerts':
        $controller = new BatchController();
        $controller->alerts();
        break;

    // Sortie de stock
    case 'stock_out':
        $controller = new StockController();
        $controller->out();
        break;

    // Rapport des pertes
    case 'report_losses':
        $controller = new ReportController();
        $controller->losses();
        break;

    // Utilisateurs
    case 'users':
        $controller = new AuthController();
        $controller->listUsers();
        break;

    case 'user_create':
        $controller = new AuthController();
        $controller->createUser();
        break;

    case 'user_delete':
        $controller = new AuthController();
        $controller->deleteUser();
        break;

    default:
        header('Location: index.php?page=dashboard');
        exit;
}
