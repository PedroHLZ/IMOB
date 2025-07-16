<?php
/**
 * Public front controller (index.php)
 * ----------------------------------
 * Corrigido para:
 *  - Iniciar sessão antes de qualquer saída (evita "headers already sent").
 *  - Remover caso duplicado de /login.
 *  - Centralizar verificação de autenticação.
 *  - Centralizar carregamento de config do site (DRY helper).
 *  - Garantir breaks/redirects consistentes.
 *  - Compatível com execução via servidor embutido (php -S) com router server.php ou direto.
 *
 * Requisitos:
 *  - vendor/autoload.php gerado pelo Composer.
 *  - config/database.php deve definir $pdo (PDO conectado ao SQLite ou outro DB).
 *  - Views em src/views/... conforme chamadas abaixo.
 *  - Criar view de login em src/views/auth/login.php (HTML do formulário de acesso restrito).
 */

declare(strict_types=1);

// --------------------------------------------------
// Inicialização básica
// --------------------------------------------------
// (Opcional durante debug) buffer de saída de segurança
// ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php'; // deve definir $pdo

use App\Controllers\ClientController;
use App\Controllers\ConfigController;

// --------------------------------------------------
// Funções utilitárias
// --------------------------------------------------

/**
 * Retorna o caminho limpo da URL atual (sem querystring, sem fragmento).
 */
function current_path(): string {
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    if ($path === false || $path === null) {
        return '/';
    }
    // Normalizar: remover múltiplas barras, manter barra inicial
    $path = '/' . ltrim($path, '/');
    // Remover barra final exceto raiz
    if (strlen($path) > 1) {
        $path = rtrim($path, '/');
    }
    return $path;
}

/**
 * Carrega configurações do site em array associativo [key => value].
 * Usa cache estático por request.
 */
function load_site_config(PDO $pdo): array {
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }
    $stmt = $pdo->prepare("SELECT key, value FROM site_config");
    $stmt->execute();
    $cfg = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $cfg[$row['key']] = $row['value'];
    }
    return $cache = $cfg;
}

/**
 * Helper para redirecionar e encerrar.
 */
function redirect(string $to, int $code = 302): void {
    header('Location: ' . $to, true, $code);
    exit;
}

// --------------------------------------------------
// Autenticação básica
// --------------------------------------------------
$path = current_path();

$publicRoutes = [
    '/',                // root (permitimos exibir login/redirecionar?) -> veremos abaixo
    '/login',
    '/config/add-user', // cadastro inicial
];

// Se não autenticado e rota não pública → /login
if (!isset($_SESSION['usuario']) && !in_array($path, $publicRoutes, true)) {
    redirect('/login');
}

// --------------------------------------------------
// Roteamento
// --------------------------------------------------
switch ($path) {

    // --------------------------------------------------
    // RAIZ / -> dashboard (lista clientes)
    // --------------------------------------------------
    case '/':
        // Se autenticado: lista clientes; senão: login.
        if (!isset($_SESSION['usuario'])) {
            require __DIR__ . '/../src/views/auth/login.php';
            exit;
        }
        $controller = new ClientController();
        $controller->index();
        break;

    // --------------------------------------------------
    // CLIENTES
    // --------------------------------------------------
    case '/clients':
        $controller = new ClientController();
        $controller->index();
        break;

    case '/clients/create':
        $controller = new ClientController();
        $controller->create();
        break;

    case '/clients/store':
        $controller = new ClientController();
        $controller->store();
        break;

    case '/clients/show':
        $controller = new ClientController();
        $controller->show($_GET['id'] ?? null);
        break;

    case '/clients/edit':
        $controller = new ClientController();
        $controller->edit($_GET['id'] ?? null);
        break;

    case '/clients/update':
        $controller = new ClientController();
        $controller->update($_POST['id'] ?? null);
        break;

    case '/clients/delete':
        // Excluir cliente e seus uploads
        $clientId = $_GET['id'] ?? null;
        if ($clientId) {
            $controller = new ClientController();
            $controller->delete($clientId);
            // excluir uploads
            $uploadDir = __DIR__ . "/uploads/cliente_{$clientId}";
            if (is_dir($uploadDir)) {
                $files = array_diff(scandir($uploadDir), ['.', '..']);
                foreach ($files as $file) {
                    @unlink($uploadDir . DIRECTORY_SEPARATOR . $file);
                }
                @rmdir($uploadDir);
            }
            $_SESSION['success'] = 'Cliente excluído com sucesso!';
        }
        redirect('/clients');
        break;

    case '/clients/delete-attachment':
        $clientId = $_POST['client_id'] ?? null;
        $file = $_POST['file'] ?? null;
        if ($clientId && $file) {
            $filePath = __DIR__ . "/uploads/cliente_{$clientId}/" . $file;
            if (file_exists($filePath)) {
                unlink($filePath);
                $_SESSION['success'] = 'Anexo excluído com sucesso!';
            } else {
                $_SESSION['error'] = 'Arquivo não encontrado.';
            }
        } else {
            $_SESSION['error'] = 'Dados inválidos para exclusão.';
        }
        redirect('/clients/show?id=' . urlencode((string)$clientId));
        break;

    case '/clients/pdf':
        $controller = new ClientController();
        $controller->generatePdf($_GET['id'] ?? null);
        break;

    case '/clients/pdf-list':
        $controller = new ClientController();
        $controller->generateClientsListPdf();
        break;

    // --------------------------------------------------
    // LISTA CLIENTES PÚBLICA? (/listaclientes)
    // --------------------------------------------------
    case '/listaclientes':
        $q = trim($_GET['q'] ?? '');
        if ($q !== '') {
            $stmt = $pdo->prepare("SELECT * FROM clients WHERE name LIKE ? OR cpf LIKE ? ORDER BY id DESC");
            $like = "%$q%";
            $stmt->execute([$like, $like]);
        } else {
            $stmt = $pdo->query("SELECT * FROM clients ORDER BY id DESC");
        }
        $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $config = load_site_config($pdo);
        require __DIR__ . '/../src/views/clients/list.php';
        break;

    // --------------------------------------------------
    // CLIENTES DO CORRETOR LOGADO (/meus-clientes)
    // --------------------------------------------------
    case '/meus-clientes':
        if (($_SESSION['user_level'] ?? null) !== 'corretor') {
            http_response_code(403);
            echo 'Acesso negado.';
            exit;
        }
        $corretor = $_SESSION['usuario'] ?? '';
        $stmt = $pdo->prepare("SELECT * FROM clients WHERE broker = ? ORDER BY id DESC");
        $stmt->execute([$corretor]);
        $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $config = load_site_config($pdo);
        require __DIR__ . '/../src/views/clients/meus_clientes.php';
        break;

    // --------------------------------------------------
    // LOGIN
    // --------------------------------------------------
    case '/login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['code'] ?? '');
            $stmt = $pdo->prepare('SELECT * FROM users WHERE code = ?');
            $stmt->execute([$code]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $_SESSION['usuario'] = $user['name'];
                $_SESSION['user_level'] = $user['level'] ?? 'corretor';
                redirect('/');
            } else {
                $_SESSION['error'] = 'Código inválido.';
            }
        }
        require __DIR__ . '/../src/views/auth/login.php';
        exit; // evita cair em default

    // --------------------------------------------------
    // LOGOUT
    // --------------------------------------------------
    case '/logout':
        session_destroy();
        redirect('/');
        break; // não alcançado

    // --------------------------------------------------
    // USUÁRIOS (CONFIG)
    // --------------------------------------------------
    case '/config/users':
        $controller = new ConfigController();
        $controller->users();
        break;

    case '/config/add-user':
        $controller = new ConfigController();
        $controller->addUser();
        break;

    case '/config/edit-user':
        $controller = new ConfigController();
        $controller->editUser();
        break;

    case '/config/delete-user':
        $controller = new ConfigController();
        $controller->deleteUser();
        break;

    // --------------------------------------------------
    // CONFIGURAÇÃO SITE
    // --------------------------------------------------
    case '/config/site':
        $controller = new ConfigController();
        $controller->site();
        break;

    case '/config/update-site':
        $controller = new ConfigController();
        $controller->updateSite();
        break;

    case '/config/brokers':
        $controller = new ConfigController();
        $controller->brokers();
        break;

    case '/config/add-broker':
        $controller = new ConfigController();
        $controller->addBroker();
        break;

    case '/config/delete-broker':
        $controller = new ConfigController();
        $controller->deleteBroker();
        break;

    // --------------------------------------------------
    // CONFIG LEADS
    // --------------------------------------------------
    case '/config/leads':
        // Exibir página de configuração de leads
        $stmt = $pdo->query("SELECT * FROM brokers ORDER BY name ASC");
        $brokers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $config = load_site_config($pdo);
        $selectedBrokers = [];
        if (!empty($config['leads_brokers'])) {
            $selectedBrokers = explode(',', $config['leads_brokers']);
        }
        require __DIR__ . '/../src/views/config/leads.php';
        break;

    case '/config/update-leads':
        $leadsPerBroker = (int)($_POST['leads_per_broker'] ?? 5);
        $brokers = $_POST['brokers'] ?? [];
        $brokersStr = implode(',', $brokers);
        $stmt = $pdo->prepare("REPLACE INTO site_config (key, value) VALUES (?, ?)");
        $stmt->execute(['leads_per_broker', $leadsPerBroker]);
        $stmt->execute(['leads_brokers', $brokersStr]);
        $_SESSION['success'] = 'Configurações de leads atualizadas!';
        redirect('/config/leads');
        break;

    // --------------------------------------------------
    // LEADS CRUD
    // --------------------------------------------------
    case '/leads/list':
        $leads = $pdo->query("SELECT l.*, b.name as assigned_broker_name FROM leads l LEFT JOIN brokers b ON l.assigned_broker_id = b.id ORDER BY l.id DESC")->fetchAll(PDO::FETCH_ASSOC);
        $config = load_site_config($pdo);
        require __DIR__ . '/../src/views/leads/list.php';
        break;

    case '/leads/create':
        $config = load_site_config($pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $city = trim($_POST['city'] ?? '');
            $neighborhood = trim($_POST['neighborhood'] ?? '');
            $obs = trim($_POST['obs'] ?? '');
            if ($name && $email && $phone) {
                $stmt = $pdo->prepare("INSERT INTO leads (name, email, phone, city, neighborhood, obs) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $email, $phone, $city, $neighborhood, $obs]);
                $_SESSION['success'] = 'Lead cadastrado com sucesso!';
                redirect('/leads/list');
            } else {
                $_SESSION['error'] = 'Preencha todos os campos obrigatórios!';
            }
        }
        require __DIR__ . '/../src/views/leads/create.php';
        break;

    case '/leads/delete':
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo 'ID do lead não informado.';
            exit;
        }
        $stmt = $pdo->prepare("DELETE FROM leads WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['success'] = 'Lead excluído com sucesso!';
        redirect('/leads/list');
        break;

    case '/leads/import-csv':
        $config = load_site_config($pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv'])) {
            $file = $_FILES['csv']['tmp_name'];
            $handle = fopen($file, 'r');
            $header = fgetcsv($handle, 1000, ',');
            $expected = ['nome', 'bairro', 'cidade', 'uf', 'telefone 1', 'telefone 2', 'e-mail'];
            $imported = 0;
            $headerLower = array_map('mb_strtolower', $header);
            if ($headerLower === $expected) {
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    if (count($data) >= 7) {
                        $name = trim($data[0]);
                        $neighborhood = trim($data[1]);
                        $city = trim($data[2]);
                        $phone = trim($data[4]);
                        $phone2 = trim($data[5]); // não usado
                        $email = trim($data[6]);
                        $obs = '';
                        if ($name && $email && $phone) {
                            $stmt = $pdo->prepare("INSERT INTO leads (name, email, phone, city, neighborhood, obs) VALUES (?, ?, ?, ?, ?, ?)");
                            $stmt->execute([$name, $email, $phone, $city, $neighborhood, $obs]);
                            $imported++;
                        }
                    }
                }
                $_SESSION['success'] = "$imported lead(s) importado(s) com sucesso!";
            } else {
                $_SESSION['error'] = 'Cabeçalho do CSV inválido!';
            }
            fclose($handle);
            redirect('/leads/list');
        }
        require __DIR__ . '/../src/views/leads/import_csv.php';
        break;

    case '/leads/edit':
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo 'ID do lead não informado.';
            exit;
        }
        $stmt = $pdo->prepare("SELECT * FROM leads WHERE id = ?");
        $stmt->execute([$id]);
        $lead = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$lead) {
            http_response_code(404);
            echo 'Lead não encontrado.';
            exit;
        }
        $config = load_site_config($pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $city = trim($_POST['city'] ?? '');
            $neighborhood = trim($_POST['neighborhood'] ?? '');
            $obs = trim($_POST['obs'] ?? '');
            if ($name && $email && $phone) {
                if (isset($_POST['aprovar_lead'])) {
                    // Aprovar e virar cliente
                    $cpf = empty($lead['cpf']) ? '0' : $lead['cpf'];
                    $broker = $lead['assigned_broker_id'] ?? null;
                    if ($broker) {
                        $stmtBroker = $pdo->prepare("SELECT name FROM brokers WHERE id = ?");
                        $stmtBroker->execute([$broker]);
                        $brokerName = $stmtBroker->fetchColumn();
                    } else {
                        $brokerName = null;
                    }
                    $stmt = $pdo->prepare("INSERT INTO clients (name, phone, email, observation, cpf, broker, created_at) VALUES (?, ?, ?, ?, ?, ?, datetime('now','localtime'))");
                    $stmt->execute([$name, $phone, $email, $obs, $cpf, $brokerName]);
                    $stmt = $pdo->prepare("UPDATE leads SET status = 'aprovado' WHERE id = ?");
                    $stmt->execute([$id]);
                    $_SESSION['success'] = 'Lead aprovado e cadastrado como cliente!';
                    redirect('/listaclientes');
                }
                $stmt = $pdo->prepare("UPDATE leads SET name=?, email=?, phone=?, city=?, neighborhood=?, obs=? WHERE id=?");
                $stmt->execute([$name, $email, $phone, $city, $neighborhood, $obs, $id]);
                $_SESSION['success'] = 'Lead atualizado com sucesso!';
                redirect('/leads/list');
            } else {
                $_SESSION['error'] = 'Preencha todos os campos obrigatórios!';
            }
        }
        require __DIR__ . '/../src/views/leads/edit.php';
        break;

    case '/leads/distribuir':
        // Script que distribui leads entre corretores
        require __DIR__ . '/../src/leads/distribuir.php';
        exit;

    // --------------------------------------------------
    // LEADS do corretor logado
    // --------------------------------------------------
    case '/meus-leads':
        if (($_SESSION['user_level'] ?? null) !== 'corretor') {
            http_response_code(403);
            echo 'Acesso negado.';
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['lead_id'])) {
            $leadId = (int)$_GET['lead_id'];
            if (isset($_POST['status'])) {
                $status = $_POST['status'];
                $allowed = ['distribuido', 'respondeu', 'interessado', 'nao_responde', 'nao_existe'];
                if (in_array($status, $allowed, true)) {
                    $stmt = $pdo->prepare("UPDATE leads SET status = ? WHERE id = ?");
                    $stmt->execute([$status, $leadId]);
                    $_SESSION['success'] = 'Status do lead atualizado!';
                    redirect('/meus-leads');
                }
            }
            if (isset($_GET['enviar_admin'])) {
                $stmt = $pdo->prepare("UPDATE leads SET status = 'aguardando_aprovacao' WHERE id = ?");
                $stmt->execute([$leadId]);
                $_SESSION['success'] = 'Lead enviado para aprovação do admin!';
                redirect('/meus-leads');
            }
        }
        $corretor = $_SESSION['usuario'] ?? '';
        $stmtBroker = $pdo->prepare("SELECT id FROM brokers WHERE name = ?");
        $stmtBroker->execute([$corretor]);
        $broker = $stmtBroker->fetch(PDO::FETCH_ASSOC);
        $leads = [];
        if ($broker) {
            $leads = $pdo->query("SELECT * FROM leads WHERE assigned_broker_id = " . (int)$broker['id'] . " ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        }
        $config = load_site_config($pdo);
        require __DIR__ . '/../src/views/leads/meus_leads.php';
        break;

    case '/leads/aguardando-aprovacao':
        $leads = $pdo->query("SELECT l.*, b.name as assigned_broker_name FROM leads l LEFT JOIN brokers b ON l.assigned_broker_id = b.id WHERE l.status = 'aguardando_aprovacao' ORDER BY l.id DESC")->fetchAll(PDO::FETCH_ASSOC);
        $config = load_site_config($pdo);
        require __DIR__ . '/../src/views/leads/aguardando_aprovacao.php';
        break;

    // --------------------------------------------------
    // DEFAULT 404
    // --------------------------------------------------
    default:
        http_response_code(404);
        echo 'Página não encontrada';
        break;
}

// (Se usou ob_start) descomente para liberar saída
// ob_end_flush();
