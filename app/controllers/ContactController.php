<?php
class ContactController {
    public function index() {
        $data = [
            'title' => 'Contact - PsihoService',
            'content' => 'Aici puteți găsi informații about cum să ne contactați. Ne puteți trimite un email, ne puteți suna sau ne puteți vizita la adresa noastră.'
        ];

        // Încărcăm pagina contact/aprove.php cu datele aferente
        $this->view('contact/index', $data);
    }

    public function sendMessage() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nume = trim($_POST['nume']);
            $email = trim($_POST['email']);
            $mesaj = trim($_POST['mesaj']);

            if (!empty($nume) && !empty($email) && !empty($mesaj)) {
                $to = "contact@psihoservice.ro"; // Adresa clinicii
                $subject = "Mesaj de la $nume";
                $headers = "From: $email\r\nReply-To: $email\r\nContent-Type: text/plain; charset=UTF-8";

                if (mail($to, $subject, $mesaj, $headers)) {
                    $data['successMessage'] = "Mesajul a fost trimis cu succes!";
                } else {
                    $data['errorMessage'] = "A apărut o eroare. Vă rugăm să încercați din nou.";
                }
            } else {
                $data['errorMessage'] = "Toate câmpurile sunt obligatorii!";
            }
        }


        $data['title'] = 'Contact - PsihoService';
        $data['content'] = 'Aici puteți găsi informații about cum să ne contactați.';
        $this->view('contact/index', $data);
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
