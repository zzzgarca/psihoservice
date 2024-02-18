<?php
class AboutController {
    public function index() {
        // Datele care vor fi trimise către view
        $data = [
            'title' => 'Despre Noi - PsihoService',
            'content' => 'Aici veți găsi informații despre echipa noastră și misiunea PsihoService.'
        ];
        
        // Încărcarea view-ului pentru despre noi
        $this->view('despre/index', $data);
    }

    // Metoda pentru încărcarea view-urilor
    public function view($view, $data = []) {
        require_once "app/views/{$view}.php";
    }
}
