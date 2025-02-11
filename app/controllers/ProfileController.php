<?php
class ProfileController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'logare');
            exit;
        }

        $data = [
            'email' => $_SESSION['email'],
            'role' => $_SESSION['role']
        ];

        $this->view('profile/index', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            global $pdo;

            if (!isset($_SESSION['user_id'])) {
                header('Location: ' . BASE_URL . 'logare');
                exit;
            }

            $userId = $_SESSION['user_id'];
            $email = trim($_POST['email']);
            $password = !empty($_POST['password']) ? password_hash(trim($_POST['password']), PASSWORD_DEFAULT) : null;

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $data['errorMessage'] = "Email-ul nu este valid.";
            } else {
                if ($password) {
                    $stmt = $pdo->prepare("UPDATE Users SET Email = :email, Parola = :password WHERE ID_User = :userId");
                    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                } else {
                    $stmt = $pdo->prepare("UPDATE Users SET Email = :email WHERE ID_User = :userId");
                }

                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    $_SESSION['email'] = $email;
                    $data['successMessage'] = "Profilul a fost actualizat cu succes!";
                } else {
                    $data['errorMessage'] = "A apărut o eroare. Încercați din nou.";
                }
            }

            $this->view('profile/index', $data);
        }
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
