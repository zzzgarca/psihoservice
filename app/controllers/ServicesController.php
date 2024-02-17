<?php
class ServicesController {
    public function index() {
        // Datele care vor fi trimise către view
        $data = [
            'title' => 'Serviciile Noastre - PsihoService',
            'content' => 'Aici veți găsi detalii despre serviciile oferite de PsihoService.'
        ];
        
        // Încărcarea view-ului pentru servicii
        $this->view('servicii/index', $data);
    }

    // Metoda pentru încărcarea view-urilor
    public function view($view, $data = []) {
        require_once "app/views/{$view}.php";
    }
}
