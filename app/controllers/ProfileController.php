<?php
// În app/controllers/ProfileController.php

class ProfileController {
    public function index() {
        // Asigură-te că utilizatorul este logat
        session_start();
        if (!isset($_SESSION['user_id'])) {
            // Dacă nu este logat, redirecționează la pagina de login
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        // Preia numele și rolul din sesiune
        $data = [
            'name' => $_SESSION['username'],
            'role' => $_SESSION['role']
        ];

        // Încărcarea view-ului pentru profil cu datele utilizatorului
        $this->view('profile/index', $data);
    }

    // Metoda pentru încărcarea view-urilor
    public function view($view, $data = []) {
        require_once "app/views/{$view}.php";
    }
}
