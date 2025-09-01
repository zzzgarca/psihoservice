<?php
class ContracteController
{
    public function adauga()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'psiholog') { header('Location: ' . BASE_URL . 'dashboard'); exit; }

        $pdo = require dirname(__DIR__, 2) . '/config/db.php';

        $servicii = $pdo->query("SELECT ID_Serviciu, Tip FROM Servicii ORDER BY Tip")->fetchAll();

        $stmt = $pdo->prepare("SELECT ID_User, Nume, Prenume FROM Users WHERE Rol='client' AND Status='approved' ORDER BY Nume, Prenume");
        $stmt->execute();
        $clienti = $stmt->fetchAll();

        $this->view('contracte/adauga', ['servicii' => $servicii, 'clienti' => $clienti]);
    }

    public function salveaza()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'psiholog') { header('Location: ' . BASE_URL . 'dashboard'); exit; }

        $pdo = require dirname(__DIR__, 2) . '/config/db.php';

        $prestator = $_SESSION['user_id'];
        $serviciu  = $_POST['serviciu_id'] ?? null;
        $client    = $_POST['client_id'] ?? null;
        $inceput   = $_POST['data_inceput'] ?? null;
        $sfarsit   = $_POST['data_sfarsit'] ?: null;
        $detalii   = $_POST['detalii'] ?? null;

        if ($sfarsit) {
            $dStart = DateTime::createFromFormat('Y-m-d', $inceput);
            $dEnd   = DateTime::createFromFormat('Y-m-d', $sfarsit);
            if (!$dStart || !$dEnd || $dEnd < $dStart) {
                $_SESSION['error'] = 'Data de sfârșit nu poate fi mai mică decât data de început.';
                header('Location: ' . BASE_URL . 'contracte/adauga'); exit;
            }
        }

        $sql = "INSERT INTO Contracte (ID_Prestator, ID_Client, DataInceput, DataSfarsit, ID_Serviciu, Detalii)
            VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$prestator, $client, $inceput, $sfarsit, $serviciu, $detalii]);

        header('Location: ' . BASE_URL . 'contracte/contractelemele'); exit;
    }


    public function contractelemele()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'psiholog') { header('Location: ' . BASE_URL . 'dashboard'); exit; }

        $pdo = require dirname(__DIR__, 2) . '/config/db.php';

        $sql = "SELECT c.ID_Contract, c.DataInceput, c.DataSfarsit, c.Detalii,
                       s.Tip AS Serviciu,
                       p.Nume AS NumePsiholog, p.Prenume AS PrenumePsiholog,
                       u.Nume AS NumeClient, u.Prenume AS PrenumeClient
                FROM Contracte c
                JOIN Servicii s ON s.ID_Serviciu = c.ID_Serviciu
                JOIN Users p ON p.ID_User = c.ID_Prestator
                JOIN Users u ON u.ID_User = c.ID_Client
                WHERE c.ID_Prestator = ?
                ORDER BY c.DataInceput DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_SESSION['user_id']]);
        $contracte = $stmt->fetchAll();

        $this->view('contracte/contractelemele', ['contracte' => $contracte]);
    }

    public function toate()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrator') { header('Location: ' . BASE_URL . 'dashboard'); exit; }

        $pdo = require dirname(__DIR__, 2) . '/config/db.php';

        $sql = "SELECT c.ID_Contract, c.DataInceput, c.DataSfarsit, c.Detalii,
                       s.Tip AS Serviciu,
                       p.Nume AS NumePsiholog, p.Prenume AS PrenumePsiholog,
                       u.Nume AS NumeClient, u.Prenume AS PrenumeClient
                FROM Contracte c
                JOIN Servicii s ON s.ID_Serviciu = c.ID_Serviciu
                JOIN Users p ON p.ID_User = c.ID_Prestator
                JOIN Users u ON u.ID_User = c.ID_Client
                ORDER BY c.DataInceput DESC";
        $contracte = $pdo->query($sql)->fetchAll();

        $this->view('contracte/toate', ['contracte' => $contracte]);
    }

    public function actualizeazaSfarsit()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'psiholog') { header('Location: ' . BASE_URL . 'dashboard'); exit; }

        $pdo = require dirname(__DIR__, 2) . '/config/db.php';

        $idContract = isset($_POST['contract_id']) ? (int)$_POST['contract_id'] : 0;
        $dataSfarsit = $_POST['data_sfarsit'] ?? null;
        if ($idContract <= 0) { header('Location: ' . BASE_URL . 'contracte/contractelemele'); exit; }

        $sql = "UPDATE Contracte SET DataSfarsit = ? WHERE ID_Contract = ? AND ID_Prestator = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$dataSfarsit ?: null, $idContract, $_SESSION['user_id']]);

        header('Location: ' . BASE_URL . 'contracte/contractelemele'); exit;
    }

    public function contractelemeleclient()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') { header('Location: ' . BASE_URL . 'dashboard'); exit; }

        $pdo = require dirname(__DIR__, 2) . '/config/db.php';

        $sql = "SELECT c.ID_Contract, c.DataInceput, c.DataSfarsit, c.Detalii,
                   s.Tip AS Serviciu,
                   p.Nume AS NumePsiholog, p.Prenume AS PrenumePsiholog
            FROM Contracte c
            JOIN Servicii s ON s.ID_Serviciu = c.ID_Serviciu
            JOIN Users p ON p.ID_User = c.ID_Prestator
            WHERE c.ID_Client = ?
            ORDER BY c.DataInceput DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_SESSION['user_id']]);
        $contracte = $stmt->fetchAll();

        $this->view('contracte/contractelemeleclient', ['contracte' => $contracte]);
    }



    private function view($view, $data = [])
    {
        extract($data);
        require_once dirname(__DIR__) . "/views/{$view}.php";
    }
}
