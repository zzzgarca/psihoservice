<?php
class ContactController {
    public function index() {
        // Datele care vor fi trimise către view
        $data = [
            'title' => 'Contact - PsihoService',
            'content' => 'Aici puteți găsi informații despre cum să ne contactați. Ne puteți trimite un email, ne puteți suna sau ne puteți vizita la adresa noastră.'
        ];
        
        // Încărcarea view-ului pentru contact
        $this->view('contact/index', $data);
    }

    // Metoda pentru încărcarea view-urilor
    public function view($view, $data = []) {
        require_once "app/views/{$view}.php";
    }
}
