<?php
class LoginController {
    public function index() {
        // Datele care vor fi trimise către view
        $data = [
            'title' => 'Pagina de Logare - PsihoService',
            'content' => 'Vă rugăm să vă autentificați pentru a accesa sistemul.',
        ];
        
        // Încărcarea view-ului pentru logare cu datele specificate
        $this->view('logare/index', $data);
    }

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
    
            // Aici, interoghează baza de date pentru a găsi utilizatorul
            $user = $this->getUserByUsername($username);
    
            if ($user && password_verify($password, $user['password'])) {
                // Parola este corectă, inițializează sesiunea utilizatorului
                $this->initializeUserSession($user);
                // Redirecționează către dashboard sau pagina principală
                header('Location: /dashboard');
            } else {
                // Credențiale incorecte, afișează un mesaj de eroare
                $this->view('login/index', ['errorMessage' => 'Nume de utilizator sau parolă incorectă']);
            }
        }
    }
    

    // Metoda pentru încărcarea view-urilor
    public function view($view, $data = []) {
        require_once "app/views/{$view}.php";
    }

    private function initializeUserSession($user) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $role; // Rolul utilizatorului autentificat
        // Poți adăuga și alte informații relevante în sesiune
    }

    public function logout() {
        // Asigură-te că o sesiune este activă
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Distruge toate datele înregistrate în sesiune
        session_unset();
        session_destroy();

        // Redirecționează utilizatorul către pagina de logare sau pagina principală
        header('Location: /login');
        exit;
    }
    
    
}

