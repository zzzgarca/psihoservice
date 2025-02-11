<?php
class HomeController {
    public function index() {
        $data = [
            'title' => 'Pagina Principală - PsihoService',
            'content' => 'Acesta este conținutul paginii de home.'
        ];

        $this->view('home/index', $data);
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
