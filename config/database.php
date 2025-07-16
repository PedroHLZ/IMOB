

<?php

$dbPath = __DIR__ . '/../database.sqlite';
try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

// Criar tabela se não existir


$sql = "CREATE TABLE IF NOT EXISTS clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    cpf TEXT NOT NULL,
    phone TEXT,
    email TEXT,
    birthdate TEXT,
    civil_status TEXT,
    dependent_name TEXT,
    dependent_cpf TEXT,
    dependent_birthdate TEXT,
    dependent_relationship TEXT,
    broker TEXT,
    formal_income REAL,
    informal_income REAL,
    approval_status TEXT,
    property_value REAL,
    financed_value REAL,
    subsidy_value REAL,
    total_value REAL,
    observation TEXT,
    program_mcmv TEXT,
    fgts TEXT,
    created_at TEXT DEFAULT (datetime('now','localtime'))
)";

$pdo->exec($sql);

// Criar tabela brokers se não existir
$sqlBrokers = "CREATE TABLE IF NOT EXISTS brokers (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL
)";
$pdo->exec($sqlBrokers);

// Criar tabela de configurações do site se não existir
$sqlSiteConfig = "CREATE TABLE IF NOT EXISTS site_config (
    key TEXT PRIMARY KEY,
    value TEXT
)";
$pdo->exec($sqlSiteConfig);
// Criar tabela de usuários/códigos para autenticação
$sqlUsers = "CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    code TEXT NOT NULL UNIQUE,
    level TEXT NOT NULL DEFAULT 'corretor',
    broker_id INTEGER,
    FOREIGN KEY (broker_id) REFERENCES brokers(id)
)";
$pdo->exec($sqlUsers);

// Criar tabela de leads para cadastro e distribuição
$sqlLeads = "CREATE TABLE IF NOT EXISTS leads (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL,
    phone TEXT NOT NULL,
    city TEXT,
    neighborhood TEXT,
    obs TEXT,
    assigned_broker_id INTEGER,
    created_at TEXT DEFAULT (datetime('now','localtime')),
    status TEXT DEFAULT 'novo'
)";
$pdo->exec($sqlLeads);