<?php
class RegisterController {
    public function index() {
        $this->view('register/index');
    }

    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            global $pdo;

            $nume = trim($_POST['nume']);
            $prenume = trim($_POST['prenume']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $rol = trim($_POST['rol']);


            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->view('register', ['errorMessage' => 'Email invalid.']);
                return;
            }


            $passwordHash = password_hash($password, PASSWORD_DEFAULT);


            $status = ($rol === 'client') ? 'approved' : 'pending';


            $stmt = $pdo->prepare("SELECT * FROM Users WHERE Email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $this->view('register', ['errorMessage' => 'Email-ul este deja folosit.']);
                return;
            }


            $stmt = $pdo->prepare("INSERT INTO Users (Nume, Prenume, Email, Parola, Rol, Status) VALUES (:nume, :prenume, :email, :parola, :rol, :status)");
            $stmt->bindParam(':nume', $nume);
            $stmt->bindParam(':prenume', $prenume);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':parola', $passwordHash);
            $stmt->bindParam(':rol', $rol);
            $stmt->bindParam(':status', $status);

            if ($stmt->execute()) {
                $this->view('register', ['successMessage' => 'Cont creat! Dacă ai ales „Psiholog” sau „Administrator”, trebuie să aștepți aprobarea.']);
            } else {
                $this->view('register', ['errorMessage' => 'Eroare la înregistrare.']);
            }
        }
    }

    public function view($view, $data = []) {
        extract($data);
        require_once "app/views/{$view}.php";
    }
}
