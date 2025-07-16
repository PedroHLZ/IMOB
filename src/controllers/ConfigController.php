<?php
namespace App\Controllers;

class ConfigController
{
    // --- Usuários/Códigos de acesso ---
    public function users()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT users.*, brokers.name AS broker_name FROM users LEFT JOIN brokers ON users.broker_id = brokers.id ORDER BY users.id ASC");
        $users = $stmt->fetchAll();
        // Carregar corretores para o select
        $stmtBrokers = $pdo->query("SELECT * FROM brokers ORDER BY name ASC");
        $brokers = $stmtBrokers->fetchAll();
        // Carregar config do site para o layout
        $stmtConfig = $pdo->prepare("SELECT key, value FROM site_config");
        $stmtConfig->execute();
        $config = [];
        foreach ($stmtConfig->fetchAll() as $row) {
            $config[$row['key']] = $row['value'];
        }
        require __DIR__ . '/../views/config/users.php';
    }

    public function addUser()
    {
        global $pdo;
        $name = trim($_POST['name'] ?? '');
        $code = trim($_POST['code'] ?? '');
        $level = trim($_POST['level'] ?? 'corretor');
        $broker_id = $_POST['broker_id'] ?? null;
        if ($name && $code && $level) {
            $stmt = $pdo->prepare("INSERT INTO users (name, code, level, broker_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $code, $level, $broker_id]);
        }
        header('Location: /config/users');
        exit;
    }

    public function deleteUser()
    {
        global $pdo;
        $id = $_POST['id'] ?? null;
        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
        }
        header('Location: /config/users');
        exit;
    }

    public function site()
    {
        global $pdo;
        // Recupera configuração do banco
        $stmt = $pdo->prepare("SELECT key, value FROM site_config");
        $stmt->execute();
        $config = [];
        foreach ($stmt->fetchAll() as $row) {
            $config[$row['key']] = $row['value'];
        }
        // Busca corretores
        $stmt = $pdo->query("SELECT * FROM brokers ORDER BY name ASC");
        $brokers = $stmt->fetchAll();
        require __DIR__ . '/../views/config/site.php';
    }

    public function updateSite()
    {
        global $pdo;
        $company = $_POST['company'] ?? '';
        // Salva no banco (upsert)
        $stmt = $pdo->prepare("INSERT INTO site_config (key, value) VALUES ('company', ?) ON CONFLICT(key) DO UPDATE SET value=excluded.value");
        $stmt->execute([$company]);
        header('Location: /config/site');
        exit;
    }

    public function brokers()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM brokers ORDER BY name ASC");
        $brokers = $stmt->fetchAll();
        require __DIR__ . '/../views/config/brokers.php';
    }

    public function addBroker()
    {
        global $pdo;
        $name = $_POST['name'] ?? '';
        if ($name) {
            $stmt = $pdo->prepare("INSERT INTO brokers (name) VALUES (?)");
            $stmt->execute([$name]);
        }
        header('Location: /config/brokers');
        exit;
    }

    public function deleteBroker()
    {
        global $pdo;
        $id = $_GET['id'] ?? null;
        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM brokers WHERE id = ?");
            $stmt->execute([$id]);
        }
        header('Location: /config/brokers');
        exit;
    }
    public function editUser()
    {
        global $pdo;
        $id = $_POST['id'] ?? null;
        $name = trim($_POST['name'] ?? '');
        $code = trim($_POST['code'] ?? '');
        $level = trim($_POST['level'] ?? 'corretor');
        $broker_id = $_POST['broker_id'] ?? null;
        if ($id && $name && $code && $level) {
            $stmt = $pdo->prepare("UPDATE users SET name = ?, code = ?, level = ?, broker_id = ? WHERE id = ?");
            $stmt->execute([$name, $code, $level, $broker_id, $id]);
        }
        header('Location: /config/users');
        exit;
    }
}
