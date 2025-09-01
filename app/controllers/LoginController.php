<?php
class LoginController {
    public function index() {
        $data = [
            'title' => 'Pagina de Logare - PsihoService',
            'content' => 'Vă rugăm să vă autentificați pentru a accesa sistemul.',
        ];

        $this->view('login/index', $data);
    }

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            global $pdo;

            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            // Check if the user exists
            $stmt = $pdo->prepare("SELECT * FROM Users WHERE Email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['Parola'])) {
                if ($user['Status'] !== 'approved') {
                    $this->view('login/index', ['errorMessage' => 'Contul nu este aprobat.']);
                    return;
                }

                session_start();
                $_SESSION['user_id'] = $user['ID_User'];
                $_SESSION['email'] = $user['Email'];
                $_SESSION['role'] = $user['Rol'];
                $_SESSION['nume']    = $user['Nume'];
                $_SESSION['prenume'] = $user['Prenume'];


                // Redirect to dashboard
                header('Location: ' . BASE_URL . 'dashboard');
                exit;
            } else {
                $this->view('login/index', ['errorMessage' => 'Email sau parolă incorecte.']);
            }
        }
    }

    private function getUserByEmail($email) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM Users WHERE Email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function initializeUserSession($user) {
        session_start();
        $_SESSION['user_id'] = $user['ID_User'];
        $_SESSION['email'] = $user['Email'];
        $_SESSION['role'] = $user['Rol'];
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();

        header('Location: ' . BASE_URL . 'login');
        exit;
    }

    public function view($view, $data = []) {
        $viewFile = "app/views/{$view}.php";

        if (file_exists($viewFile)) {
            extract($data);
            require_once "app/views/templates/header.php";
            require_once $viewFile;
            require_once "app/views/templates/footer.php";
        } else {
            die("View-ul {$view} nu există!");
        }
    }
}
