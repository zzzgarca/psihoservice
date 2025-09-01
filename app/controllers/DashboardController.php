<?php
class DashboardController
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        switch ($_SESSION['role']) {
            case 'psiholog':
                header('Location: ' . BASE_URL . 'dashboard/psiholog');
                break;
            case 'client':
                header('Location: ' . BASE_URL . 'dashboard/client');
                break;
            case 'administrator':
                header('Location: ' . BASE_URL . 'dashboard/admin');
                break;
            default:
                header('Location: ' . BASE_URL);
        }
        exit;
    }

    public function admin()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrator') {
            header('Location: ' . BASE_URL . 'dashboard');
            exit;
        }
        $this->view('dashboard/admin');
    }

    public function psiholog()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'psiholog') {
            header('Location: ' . BASE_URL . 'dashboard');
            exit;
        }
        $this->view('dashboard/psiholog');
    }

    public function client()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
            header('Location: ' . BASE_URL . 'dashboard');
            exit;
        }
        $this->view('dashboard/client');
    }

    private function view($view, $data = [])
    {
        extract($data);
        require_once "app/views/{$view}.php";
    }
}
