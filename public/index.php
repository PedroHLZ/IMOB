<?php
declare(strict_types=1);

// Iniciar sessão antes de qualquer saída
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';




$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);

switch ($path) {

    case '/leads/delete':
        // Excluir lead pelo ID
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo 'ID do lead não informado.';
            exit;
        }
        $stmt = $pdo->prepare("DELETE FROM leads WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['success'] = 'Lead excluído com sucesso!';
        header('Location: /leads/list');
        exit;
    case '/config/site':
        $controller = new App\Controllers\ConfigController();
        $controller->site();
        break;
    case '/config/update-site':
        $controller = new App\Controllers\ConfigController();
        $controller->updateSite();
        break;
    case '/config/brokers':
        $controller = new App\Controllers\ConfigController();
        $controller->brokers();
        break;
    case '/config/add-broker':
        $controller = new App\Controllers\ConfigController();
        $controller->addBroker();
        break;
    case '/config/delete-broker':
        $controller = new App\Controllers\ConfigController();
        $controller->deleteBroker();
        break;
    case '/':
    case '/public':
        $controller = new App\Controllers\ClientController();
        $controller->index();
        break;
    case '/clients':
        $controller = new App\Controllers\ClientController();
        $controller->index();
        break;
    case '/clients/create':
        $controller = new App\Controllers\ClientController();
        $controller->create();
        break;
    case '/clients/store':
        $controller = new App\Controllers\ClientController();
        $controller->store();
        break;
    case '/clients/show':
        $controller = new App\Controllers\ClientController();
        $controller->show($_GET['id']);
        break;
    case '/clients/edit':
        $controller = new App\Controllers\ClientController();
        $controller->edit($_GET['id']);
        break;
    case '/clients/update':
        $controller = new App\Controllers\ClientController();
        $controller->update($_POST['id']);
        break;
    case '/clients/delete':
        // Excluir cliente e sua pasta de uploads
        $clientId = $_GET['id'] ?? null;
        if ($clientId) {
            // Excluir do banco
            $controller = new App\Controllers\ClientController();
            $controller->delete($clientId);
            // Excluir pasta de uploads
            $uploadDir = __DIR__ . "/uploads/cliente_{$clientId}";
            if (is_dir($uploadDir)) {
                $files = array_diff(scandir($uploadDir), ['.', '..']);
                foreach ($files as $file) {
                    @unlink($uploadDir . DIRECTORY_SEPARATOR . $file);
                }
                @rmdir($uploadDir);
            }
        }
        break;
    case '/clients/pdf':
        $controller = new App\Controllers\ClientController();
        $controller->generatePdf($_GET['id']);
        break;
    case '/clients/pdf-list':
        $controller = new App\Controllers\ClientController();
        $controller->generateClientsListPdf();
        break;
    case '/config/users':
        $controller = new App\Controllers\ConfigController();
        $controller->users();
        break;
    case '/meus-clientes':
        // Só permite acesso se for corretor
        if (($_SESSION['user_level'] ?? null) !== 'corretor') {
            http_response_code(403);
            echo 'Acesso negado.';
            exit;
        }
        // Buscar clientes do corretor logado
        $corretor = $_SESSION['usuario'] ?? '';
        $stmt = $pdo->prepare("SELECT * FROM clients WHERE broker = ? ORDER BY id DESC");
        $stmt->execute([$corretor]);
        $clients = $stmt->fetchAll();
        // Carregar config do site para o layout
        $stmtConfig = $pdo->prepare("SELECT key, value FROM site_config");
        $stmtConfig->execute();
        $config = [];
        foreach ($stmtConfig->fetchAll() as $row) {
            $config[$row['key']] = $row['value'];
        }
        require __DIR__ . '/../src/views/clients/meus_clientes.php';
        break;

    // Rota de login
    case '/login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['code'] ?? '');
            $stmt = $pdo->prepare('SELECT * FROM users WHERE code = ?');
            $stmt->execute([$code]);
            $user = $stmt->fetch();
            if ($user) {
                $_SESSION['usuario'] = $user['name'];
                $_SESSION['user_level'] = $user['level'] ?? 'corretor';
                header('Location: /');
                exit;
            } else {
                $_SESSION['error'] = 'Código inválido.';
            }
        }
        // Exibir formulário de login

        case '/login':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // ... (código de autenticação permanece igual) ...
    }
    
    echo <<<HTML
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Acesso Restrito</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <style>
            body {
                background: linear-gradient(45deg, #3e61eeff, #1caf9cff, #1a2a6c);
                background-size: 400% 400%;
                animation: gradientBG 15s ease infinite;
                height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            @keyframes gradientBG {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            
            .login-card {
                width: 100%;
                max-width: 450px;
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(10px);
                border-radius: 15px;
                overflow: hidden;
                box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
                transition: transform 0.3s ease;
            }
            
            .login-card:hover {
                transform: translateY(-5px);
            }
            
            .card-header {
                background: linear-gradient(to right, #1a2a6c, #1caf9cff);
                color: white;
                text-align: center;
                padding: 25px 20px;
                border-bottom: none;
            }
            
            .card-body {
                padding: 30px;
            }
            
            .form-control {
                border-radius: 50px;
                padding: 12px 20px 12px 45px;
                border: 2px solid #1caf9cff;
                transition: all 0.3s;
            }
            
            .form-control:focus {
                border-color: #1a2a6c;
                box-shadow: 0 0 0 0.25rem rgba(26, 42, 108, 0.25);
            }
            
            .input-group-text {
                position: absolute;
                z-index: 5;
                background: transparent;
                border: none;
                top: 12px;
                left: 15px;
                color: #6c757d;
            }
            
            .btn-login {
                background: linear-gradient(to right, #1a2a6c, #1caf9cff);
                border: none;
                border-radius: 50px;
                padding: 12px;
                font-weight: 600;
                transition: all 0.3s;
            }
            
            .btn-login:hover {
                background: linear-gradient(to right, #1caf9cff, #1caf9cff);
                transform: scale(1.02);
            }
            
            .password-toggle {
                position: absolute;
                right: 15px;
                top: 12px;
                cursor: pointer;
                color: #6c757d;
                z-index: 10;
            }
        </style>
    </head>
    <body>
        <div class="login-card">
            <div class="card-header">
                <h2><i class="fas fa-lock me-2"></i>ACESSO RESTRITO</h2>
                <p class="mb-0">Insira seu código de acesso</p>
            </div>
            
            <div class="card-body">
                <form method="POST">
                    <div class="mb-4 position-relative">
                        <div class="position-relative">
                            <span class="input-group-text">
                                <i class="fas fa-key"></i>
                            </span>
                            <input 
                                type="password" 
                                name="code" 
                                id="password" 
                                class="form-control ps-5" 
                                placeholder="Código de acesso" 
                                required
                            >
                            <span class="password-toggle" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-login btn-block w-100 text-white">
                        <i class="fas fa-sign-in-alt me-2"></i>ENTRAR NO SISTEMA
                    </button>
                </form>
            </div>
        </div>

        <script>
            // Toggle para mostrar/esconder senha
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#password');
            
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });
        </script>
    </body>
    </html>
    HTML;
    exit;


    case '/config/add-user':
        $controller = new App\Controllers\ConfigController();
        $controller->addUser();
        break;
    case '/config/delete-user':
        $controller = new App\Controllers\ConfigController();
        $controller->deleteUser();
        break;

    case '/logout':
        session_destroy();
        header('Location: /');
        exit;

    case '/listaclientes':
        // Filtro de busca por nome ou CPF
        $q = trim($_GET['q'] ?? '');
        if ($q !== '') {
            $stmt = $pdo->prepare("SELECT * FROM clients WHERE name LIKE ? OR cpf LIKE ? ORDER BY id DESC");
            $like = "%$q%";
            $stmt->execute([$like, $like]);
        } else {
            $stmt = $pdo->query("SELECT * FROM clients ORDER BY id DESC");
        }
        $clients = $stmt->fetchAll();
        // Carregar config do site para o layout
        $stmtConfig = $pdo->prepare("SELECT key, value FROM site_config");
        $stmtConfig->execute();
        $config = [];
        foreach ($stmtConfig->fetchAll() as $row) {
            $config[$row['key']] = $row['value'];
        }
        require __DIR__ . '/../src/views/clients/list.php';
        break;

    case '/config/edit-user':
        $controller = new App\Controllers\ConfigController();
        $controller->editUser();
        break;





    case '/leads/list':
        // Listar todos os leads
        $leads = $pdo->query("SELECT l.*, b.name as assigned_broker_name FROM leads l LEFT JOIN brokers b ON l.assigned_broker_id = b.id ORDER BY l.id DESC")->fetchAll();
        $stmtConfig = $pdo->prepare("SELECT key, value FROM site_config");
        $stmtConfig->execute();
        $config = [];
        foreach ($stmtConfig->fetchAll() as $row) {
            $config[$row['key']] = $row['value'];
        }
        require __DIR__ . '/../src/views/leads/list.php';
        break;

    case '/leads/create':
        // Exibir formulário de cadastro de lead
        $stmtConfig = $pdo->prepare("SELECT key, value FROM site_config");
        $stmtConfig->execute();
        $config = [];
        foreach ($stmtConfig->fetchAll() as $row) {
            $config[$row['key']] = $row['value'];
        }
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
                header('Location: /leads/list');
                exit;
            } else {
                $_SESSION['error'] = 'Preencha todos os campos obrigatórios!';
            }
        }
        require __DIR__ . '/../src/views/leads/create.php';
        break;

    case '/leads/import-csv':
        $stmtConfig = $pdo->prepare("SELECT key, value FROM site_config");
        $stmtConfig->execute();
        $config = [];
        foreach ($stmtConfig->fetchAll() as $row) {
            $config[$row['key']] = $row['value'];
        }
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
                        // $uf = trim($data[3]); // UF não será salvo
                        $phone = trim($data[4]);
                        $phone2 = trim($data[5]);
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
            header('Location: /leads/list');
            exit;
        }
        require __DIR__ . '/../src/views/leads/import_csv.php';
        break;

    case '/leads/distribuir':
        // Executar distribuição dos leads
        require __DIR__ . '/../src/leads/distribuir.php';
        exit;

    case '/leads/edit':
        // Editar lead
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo 'ID do lead não informado.';
            exit;
        }
        $stmt = $pdo->prepare("SELECT * FROM leads WHERE id = ?");
        $stmt->execute([$id]);
        $lead = $stmt->fetch();
        if (!$lead) {
            http_response_code(404);
            echo 'Lead não encontrado.';
            exit;
        }
        $stmtConfig = $pdo->prepare("SELECT key, value FROM site_config");
        $stmtConfig->execute();
        $config = [];
        foreach ($stmtConfig->fetchAll() as $row) {
            $config[$row['key']] = $row['value'];
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $city = trim($_POST['city'] ?? '');
            $neighborhood = trim($_POST['neighborhood'] ?? '');
            $obs = trim($_POST['obs'] ?? '');
            if ($name && $email && $phone) {
                // Aprovar e virar cliente
                if (isset($_POST['aprovar_lead'])) {
                    // Preenche CPF como '0' se estiver vazio ou nulo
                    $cpf = empty($lead['cpf']) ? '0' : $lead['cpf'];
                    // Descobre o corretor responsável pelo lead
                    $broker = $lead['assigned_broker_id'] ?? null;
                    // Busca o nome do corretor se o campo existir
                    if ($broker) {
                        $stmtBroker = $pdo->prepare("SELECT name FROM brokers WHERE id = ?");
                        $stmtBroker->execute([$broker]);
                        $brokerName = $stmtBroker->fetchColumn();
                    } else {
                        $brokerName = null;
                    }
                    $stmt = $pdo->prepare("INSERT INTO clients (name, phone, email, observation, cpf, broker, created_at) VALUES (?, ?, ?, ?, ?, ?, datetime('now','localtime'))");
                    $stmt->execute([$name, $phone, $email, $obs, $cpf, $brokerName]);
                    // Atualizar status do lead
                    $stmt = $pdo->prepare("UPDATE leads SET status = 'aprovado' WHERE id = ?");
                    $stmt->execute([$id]);
                    $_SESSION['success'] = 'Lead aprovado e cadastrado como cliente!';
                    header('Location: /listaclientes');
                    exit;
                }
                $stmt = $pdo->prepare("UPDATE leads SET name=?, email=?, phone=?, city=?, neighborhood=?, obs=? WHERE id=?");
                $stmt->execute([$name, $email, $phone, $city, $neighborhood, $obs, $id]);
                $_SESSION['success'] = 'Lead atualizado com sucesso!';
                header('Location: /leads/list');
                exit;
            } else {
                $_SESSION['error'] = 'Preencha todos os campos obrigatórios!';
            }
        }
        require __DIR__ . '/../src/views/leads/edit.php';
        break;

    case '/meus-leads':
        // Só permite acesso se for corretor
        if (($_SESSION['user_level'] ?? null) !== 'corretor') {
            http_response_code(403);
            echo 'Acesso negado.';
            exit;
        }
        // Atualizar status do lead se enviado via POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['lead_id'])) {
            $leadId = (int)$_GET['lead_id'];
            // Atualizar status
            if (isset($_POST['status'])) {
                $status = $_POST['status'];
                $allowed = ['distribuido', 'respondeu', 'interessado', 'nao_responde', 'nao_existe'];
                if (in_array($status, $allowed, true)) {
                    $stmt = $pdo->prepare("UPDATE leads SET status = ? WHERE id = ?");
                    $stmt->execute([$status, $leadId]);
                    $_SESSION['success'] = 'Status do lead atualizado!';
                    header('Location: /meus-leads');
                    exit;
                }
            }
            // Enviar para admin
            if (isset($_GET['enviar_admin'])) {
                $stmt = $pdo->prepare("UPDATE leads SET status = 'aguardando_aprovacao' WHERE id = ?");
                $stmt->execute([$leadId]);
                $_SESSION['success'] = 'Lead enviado para aprovação do admin!';
                header('Location: /meus-leads');
                exit;
            }
        }
        // Buscar leads do corretor logado
        $corretor = $_SESSION['usuario'] ?? '';
        // Descobrir o id do corretor
        $stmtBroker = $pdo->prepare("SELECT id FROM brokers WHERE name = ?");
        $stmtBroker->execute([$corretor]);
        $broker = $stmtBroker->fetch();
        $leads = [];
        if ($broker) {
            $leads = $pdo->query("SELECT * FROM leads WHERE assigned_broker_id = " . (int)$broker['id'] . " ORDER BY id DESC")->fetchAll();
        }
        // Carregar config do site para o layout
        $stmtConfig = $pdo->prepare("SELECT key, value FROM site_config");
        $stmtConfig->execute();
        $config = [];
        foreach ($stmtConfig->fetchAll() as $row) {
            $config[$row['key']] = $row['value'];
        }
        require __DIR__ . '/../src/views/leads/meus_leads.php';
        break;

    case '/leads/aguardando-aprovacao':
        // Listar leads aguardando aprovação
        $leads = $pdo->query("SELECT l.*, b.name as assigned_broker_name FROM leads l LEFT JOIN brokers b ON l.assigned_broker_id = b.id WHERE l.status = 'aguardando_aprovacao' ORDER BY l.id DESC")->fetchAll();
        $stmtConfig = $pdo->prepare("SELECT key, value FROM site_config");
        $stmtConfig->execute();
        $config = [];
        foreach ($stmtConfig->fetchAll() as $row) {
            $config[$row['key']] = $row['value'];
        }
        require __DIR__ . '/../src/views/leads/aguardando_aprovacao.php';
        break;
    case '/config/leads':
        // Exibir página de configuração de leads
        $stmt = $pdo->query("SELECT * FROM brokers ORDER BY name ASC");
        $brokers = $stmt->fetchAll();
        // Carregar config do site
        $stmtConfig = $pdo->prepare("SELECT key, value FROM site_config");
        $stmtConfig->execute();
        $config = [];
        foreach ($stmtConfig->fetchAll() as $row) {
            $config[$row['key']] = $row['value'];
        }
        // Carregar corretores selecionados
        $selectedBrokers = [];
        if (!empty($config['leads_brokers'])) {
            $selectedBrokers = explode(',', $config['leads_brokers']);
        }
        require __DIR__ . '/../src/views/config/leads.php';
        break;
    case '/config/update-leads':
        // Salvar configurações de leads
        $leadsPerBroker = (int)($_POST['leads_per_broker'] ?? 5);
        $brokers = $_POST['brokers'] ?? [];
        $brokersStr = implode(',', $brokers);
        // Atualiza ou insere as configs
        $stmt = $pdo->prepare("REPLACE INTO site_config (key, value) VALUES (?, ?)");
        $stmt->execute(['leads_per_broker', $leadsPerBroker]);
        $stmt->execute(['leads_brokers', $brokersStr]);
        $_SESSION['success'] = 'Configurações de leads atualizadas!';
        header('Location: /config/leads');
        exit;

    case '/clients/delete-attachment':
        // Excluir anexo do cliente
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
        header('Location: /clients/show?id=' . urlencode($clientId));
        exit;


    default:
        http_response_code(404);
        echo "Página não encontrada";
        break;
}
