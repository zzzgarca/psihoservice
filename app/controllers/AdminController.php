<?php
class AdminController {
    public function approveUsers() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrator') {
            header('Location: ' . BASE_URL . 'dashboard');
            exit;
        }

        global $pdo;
        $stmt = $pdo->query("SELECT * FROM Users WHERE Status = 'pending'");
        $pendingUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('admin/approve_users', ['pendingUsers' => $pendingUsers]);
    }

    public function approve($userId) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrator') {
            header('Location: ' . BASE_URL . 'dashboard');
            exit;
        }

        global $pdo;
        $stmt = $pdo->prepare("UPDATE Users SET Status = 'approved' WHERE ID_User = :userId");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        header('Location: ' . BASE_URL . 'admin/approve_users');
        exit;
    }

    public function reject($userId) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrator') {
            header('Location: ' . BASE_URL . 'dashboard');
            exit;
        }

        global $pdo;
        $stmt = $pdo->prepare("UPDATE Users SET Status = 'rejected' WHERE ID_User = :userId");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        header('Location: ' . BASE_URL . 'admin/approve_users');
        exit;
    }

    private function view($view, $data = []) {
        extract($data);
        require_once "app/views/{$view}.php";
    }
}
