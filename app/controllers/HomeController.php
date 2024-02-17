<?php
class HomeController {
    public function index() {
        // Datele care vor fi trimise către view
        $data = [
            'title' => 'Bine ai venit!',
            'content' => 'Acesta este conținutul paginii de home.'
        ];
        
        // Încărcarea view-ului
        $this->view('home/index', $data);
    }

    // Metoda pentru încărcarea view-urilor
    public function view($view, $data = []) {
        require_once "app/views/{$view}.php";
    }
}
