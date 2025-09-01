<?php
class SedinteController
{
    public function adauga()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'psiholog') { header('Location: ' . BASE_URL . 'dashboard'); exit; }

        $pdo = require dirname(__DIR__, 2) . '/config/db.php';

        $stmt = $pdo->prepare("SELECT ID_User, Nume, Prenume FROM Users WHERE Rol='client' AND Status='approved' ORDER BY Nume, Prenume");
        $stmt->execute();
        $clienti = $stmt->fetchAll();

        $stmt = $pdo->prepare("SELECT c.ID_Contract, u.Nume, u.Prenume, c.ID_Client
                               FROM Contracte c
                               JOIN Users u ON u.ID_User = c.ID_Client
                               WHERE c.ID_Prestator = ?
                               ORDER BY c.ID_Contract DESC");
        $stmt->execute([$_SESSION['user_id']]);
        $contracte = $stmt->fetchAll();

        $servicii = $pdo->query("SELECT ID_Serviciu, Tip FROM Servicii ORDER BY Tip")->fetchAll();

        $this->view('sedinte/adauga', ['clienti'=>$clienti, 'contracte'=>$contracte, 'servicii'=>$servicii]);
    }

    public function salveaza()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'psiholog') { header('Location: ' . BASE_URL . 'dashboard'); exit; }

        $pdo = require dirname(__DIR__, 2) . '/config/db.php';

        $prestator = $_SESSION['user_id'];
        $client    = $_POST['client_id'] ?? null;
        $contract  = $_POST['contract_id'] ?? null;
        $serviciu  = $_POST['serviciu_id'] ?? null;
        $dt        = $_POST['data_ora'] ?? null;
        $notite    = $_POST['notite'] ?? null;

        $dataOra = $dt ? str_replace('T', ' ', $dt) : null;
        if ($dataOra && strlen($dataOra) === 16) $dataOra .= ':00';

        $stmt = $pdo->prepare("SELECT ID_Contract, ID_Client FROM Contracte WHERE ID_Contract = ? AND ID_Prestator = ?");
        $stmt->execute([$contract, $prestator]);
        $row = $stmt->fetch();
        if (!$row || (int)$row['ID_Client'] !== (int)$client) { $_SESSION['error'] = 'Contract invalid pentru acest client.'; header('Location: ' . BASE_URL . 'sedinte/adauga'); exit; }

        $stmt = $pdo->prepare("SELECT 1 FROM Servicii WHERE ID_Serviciu = ? LIMIT 1");
        $stmt->execute([$serviciu]);
        if (!$stmt->fetch()) { $_SESSION['error'] = 'Serviciu invalid.'; header('Location: ' . BASE_URL . 'sedinte/adauga'); exit; }

        $check = $pdo->prepare("SELECT 1 FROM Sedinte WHERE ID_Prestator = ? AND DataOra = ? LIMIT 1");
        $check->execute([$prestator, $dataOra]);
        if ($check->fetch()) { $_SESSION['error'] = 'Există deja o ședință pentru această dată și oră la acest psiholog.'; header('Location: ' . BASE_URL . 'sedinte/adauga'); exit; }

        $check = $pdo->prepare("SELECT 1 FROM Sedinte WHERE ID_Client = ? AND DataOra = ? LIMIT 1");
        $check->execute([$client, $dataOra]);
        if ($check->fetch()) { $_SESSION['error'] = 'Clientul are deja o ședință la această dată și oră.'; header('Location: ' . BASE_URL . 'sedinte/adauga'); exit; }

        $sql = "INSERT INTO Sedinte (ID_Prestator, ID_Client, ID_Contract, ID_Serviciu, DataOra, Notite)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$prestator, $client, $contract, $serviciu, $dataOra, $notite]);

        header('Location: ' . BASE_URL . 'sedinte/sedintelemele'); exit;
    }

    public function sedintelemele()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'psiholog') { header('Location: ' . BASE_URL . 'dashboard'); exit; }

        $pdo = require dirname(__DIR__, 2) . '/config/db.php';

        $sql = "SELECT s.ID_Sedinta, s.DataOra, s.Notite, s.Finalizata, s.FacturaEmisa, s.Platita,
                       sv.Tip AS Serviciu, c.ID_Contract,
                       u.Nume AS NumeClient, u.Prenume AS PrenumeClient,
                       f.Suma AS SumaFactura, p.Suma AS SumaPlata, tp.Cod AS MetodaPlata, p.ID_TipPlata
                FROM Sedinte s
                JOIN Users u   ON u.ID_User = s.ID_Client
                LEFT JOIN Servicii sv ON sv.ID_Serviciu = s.ID_Serviciu
                LEFT JOIN Contracte c ON c.ID_Contract = s.ID_Contract
                LEFT JOIN Facturi f ON f.ID_Sedinta = s.ID_Sedinta
                LEFT JOIN Plati   p ON p.ID_Sedinta = s.ID_Sedinta
                LEFT JOIN TipPlata tp ON tp.ID_TipPlata = p.ID_TipPlata
                WHERE s.ID_Prestator = ?
                ORDER BY s.DataOra DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_SESSION['user_id']]);
        $sedinte = $stmt->fetchAll();

        $tipuri = $pdo->query("SELECT ID_TipPlata, Cod FROM TipPlata ORDER BY Cod")->fetchAll();

        $this->view('sedinte/sedintelemele', ['sedinte'=>$sedinte, 'tipuri'=>$tipuri]);
    }

    public function actualizeazaData()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'psiholog') { header('Location: ' . BASE_URL . 'dashboard'); exit; }

        $pdo = require dirname(__DIR__, 2) . '/config/db.php';

        $id  = isset($_POST['sedinta_id']) ? (int)$_POST['sedinta_id'] : 0;
        $dt  = $_POST['data_ora'] ?? null;
        $dataOra = $dt ? str_replace('T', ' ', $dt) : null;
        if ($dataOra && strlen($dataOra) === 16) $dataOra .= ':00';

        $check = $pdo->prepare("SELECT 1 FROM Sedinte WHERE ID_Prestator=? AND DataOra=? AND ID_Sedinta<>? LIMIT 1");
        $check->execute([$_SESSION['user_id'], $dataOra, $id]);
        if ($check->fetch()) { $_SESSION['error'] = 'Există deja o ședință la această dată și oră.'; header('Location: ' . BASE_URL . 'sedinte/sedintelemele'); exit; }

        $stmt = $pdo->prepare("SELECT ID_Client FROM Sedinte WHERE ID_Sedinta=? AND ID_Prestator=?");
        $stmt->execute([$id, $_SESSION['user_id']]);
        $row = $stmt->fetch();
        if (!$row) { header('Location: ' . BASE_URL . 'sedinte/sedintelemele'); exit; }

        $check2 = $pdo->prepare("SELECT 1 FROM Sedinte WHERE ID_Client=? AND DataOra=? AND ID_Sedinta<>? LIMIT 1");
        $check2->execute([(int)$row['ID_Client'], $dataOra, $id]);
        if ($check2->fetch()) { $_SESSION['error'] = 'Clientul are deja o ședință la această dată și oră.'; header('Location: ' . BASE_URL . 'sedinte/sedintelemele'); exit; }

        $upd = $pdo->prepare("UPDATE Sedinte SET DataOra=? WHERE ID_Sedinta=? AND ID_Prestator=?");
        $upd->execute([$dataOra, $id, $_SESSION['user_id']]);

        header('Location: ' . BASE_URL . 'sedinte/sedintelemele'); exit;
    }


    public function actualizeazaNotite()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'psiholog') { header('Location: ' . BASE_URL . 'dashboard'); exit; }

        $pdo = require dirname(__DIR__, 2) . '/config/db.php';

        $id     = isset($_POST['sedinta_id']) ? (int)$_POST['sedinta_id'] : 0;
        $notite = $_POST['notite'] ?? null;

        $stmt = $pdo->prepare("UPDATE Sedinte SET Notite=? WHERE ID_Sedinta=? AND ID_Prestator=?");
        $stmt->execute([$notite, $id, $_SESSION['user_id']]);

        header('Location: ' . BASE_URL . 'sedinte/sedintelemele'); exit;
    }

    public function operatiuni()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'psiholog') { header('Location: ' . BASE_URL . 'dashboard'); exit; }

        $pdo = require dirname(__DIR__, 2) . '/config/db.php';

        $id   = isset($_POST['sedinta_id']) ? (int)$_POST['sedinta_id'] : 0;
        $do   = $_POST['do'] ?? '';
        $suma = isset($_POST['suma']) ? (float)$_POST['suma'] : 0.0;
        $tipId = isset($_POST['tip_plata']) ? (int)$_POST['tip_plata'] : 0;

        $stmt = $pdo->prepare("SELECT 1 FROM Sedinte WHERE ID_Sedinta=? AND ID_Prestator=?");
        $stmt->execute([$id, $_SESSION['user_id']]);
        if (!$stmt->fetch()) { header('Location: ' . BASE_URL . 'sedinte/sedintelemele'); exit; }

        if ($do === 'factura') {
            $f = $pdo->prepare("INSERT INTO Facturi (ID_Sedinta, Suma, DataEmiterii, Statut)
                            VALUES (?, ?, CURDATE(), 'emisa')
                            ON DUPLICATE KEY UPDATE Suma=VALUES(Suma), DataEmiterii=VALUES(DataEmiterii), Statut='emisa'");
            $f->execute([$id, $suma]);

            $u = $pdo->prepare("UPDATE Sedinte SET Finalizata=1, FacturaEmisa=1 WHERE ID_Sedinta=? AND ID_Prestator=?");
            $u->execute([$id, $_SESSION['user_id']]);
        } elseif ($do === 'plata') {
            if ($tipId <= 0) { $_SESSION['error'] = 'Selectează metoda de plată.'; header('Location: ' . BASE_URL . 'sedinte/sedintelemele'); exit; }

            $p = $pdo->prepare("INSERT INTO Plati (ID_Sedinta, Suma, DataPlatii, Statut, ID_TipPlata)
                            VALUES (?, ?, CURDATE(), 'platit', ?)
                            ON DUPLICATE KEY UPDATE Suma=VALUES(Suma), DataPlatii=VALUES(DataPlatii), Statut='platit', ID_TipPlata=VALUES(ID_TipPlata)");
            $p->execute([$id, $suma, $tipId]);

            $u = $pdo->prepare("UPDATE Sedinte SET Platita=1 WHERE ID_Sedinta=? AND ID_Prestator=?");
            $u->execute([$id, $_SESSION['user_id']]);
        }

        header('Location: ' . BASE_URL . 'sedinte/sedintelemele'); exit;
    }


    private function view($view, $data = [])
    {
        extract($data);
        require_once dirname(__DIR__) . "/views/{$view}.php";
    }
}
