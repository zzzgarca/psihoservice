<?php
class AboutController {
    public function index() {
        $data = [
            'title' => 'Despre Noi - PsihoService',
            'content' => 'Aici veți găsi informații despre echipa noastră și misiunea PsihoService.'
        ];

        $this->view('about/index', $data);
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
