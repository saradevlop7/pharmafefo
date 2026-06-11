<?php

require_once __DIR__ . '/../Repository/UserRepository.php';

class AuthController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function login(): void
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($username === '' || $password === '') {
                $error = 'Veuillez remplir tous les champs.';
            } else {
                $user = $this->userRepository->findByUsername($username);

                if ($user && password_verify($password, $user->getPassword())) {
                    $_SESSION['user_id'] = $user->getId();
                    $_SESSION['username'] = $user->getUsername();
                    $_SESSION['role'] = $user->getRole();
                    header('Location: index.php?page=dashboard');
                    exit;
                } else {
                    $error = 'Identifiants incorrects.';
                }
            }
        }

        require __DIR__ . '/../../templates/login.php';
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }

    public function listUsers(): void
    {
        $this->requireRole('ADMIN');
        $users = $this->userRepository->findAll();
        require __DIR__ . '/../../templates/users.php';
    }

    public function createUser(): void
    {
        $this->requireRole('ADMIN');
        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? '';

            if ($username === '' || $password === '' || $role === '') {
                $error = 'Veuillez remplir tous les champs.';
            } elseif ($this->userRepository->findByUsername($username)) {
                $error = 'Ce nom d\'utilisateur existe déjà.';
            } else {
                $user = new User();
                $user->setUsername($username);
                $user->setPassword(password_hash($password, PASSWORD_BCRYPT));
                $user->setRole($role);
                $this->userRepository->create($user);
                $success = 'Utilisateur créé avec succès.';
            }
        }

        require __DIR__ . '/../../templates/user_form.php';
    }

    public function deleteUser(): void
    {
        $this->requireRole('ADMIN');
        $id = (int) ($_GET['id'] ?? 0);

        if ($id > 0 && $id !== (int) $_SESSION['user_id']) {
            $this->userRepository->delete($id);
        }

        header('Location: index.php?page=users');
        exit;
    }

    private function requireRole(string $role): void
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
            header('Location: index.php?page=dashboard');
            exit;
        }
    }
}
