<?php
class ServicesController {
    public function index() {
        global $pdo;

        $stmt = $pdo->query("SELECT * FROM Servicii");
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [
            'title' => 'Serviciile Noastre - PsihoService',
            'content' => 'Aici veți găsi detalii about serviciile oferite de PsihoService.',
            'services' => $services
        ];

        $this->view('services/index', $data);
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
