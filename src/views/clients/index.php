<?php
ob_start();
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Autenticação por código via banco de dados
if (!isset($_SESSION['usuario'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo'])) {
        $codigo = trim($_POST['codigo']);
        global $pdo;
        $stmt = $pdo->prepare("SELECT name, level FROM users WHERE code = ? LIMIT 1");
        $stmt->execute([$codigo]);
        $user = $stmt->fetch();
        if ($user) {
            $_SESSION['usuario'] = $user['name'];
            $_SESSION['user_level'] = $user['level'] ?? 'corretor';
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        } else {
            $erro = 'Código inválido!';
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Acesso Restrito</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            :root {
                --primary-gradient: linear-gradient(135deg, #1a2a6c, #b21f1f);
                --secondary-gradient: linear-gradient(135deg, #0d1a4a, #8a1919);
            }
            
            body {
                background: url('https://images.unsplash.com/photo-1516156008625-3a9d6067fab5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80') no-repeat center center fixed;
                background-size: cover;
                height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
            }
            
            body::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.6);
                z-index: 0;
            }
            
            .login-container {
                position: relative;
                z-index: 1;
                width: 100%;
                max-width: 450px;
                padding: 0 15px;
            }
            
            .login-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border-radius: 15px;
                overflow: hidden;
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
                transform: translateY(0);
                transition: transform 0.3s ease;
            }
            
            .login-card:hover {
                transform: translateY(-5px);
            }
            
            .card-header {
                background: var(--primary-gradient);
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
                border: 2px solid #e9ecef;
                transition: all 0.3s;
            }
            
            .form-control:focus {
                border-color: #1a2a6c;
                box-shadow: 0 0 0 0.25rem rgba(26, 42, 108, 0.25);
            }
            
            .input-icon {
                position: absolute;
                z-index: 5;
                top: 12px;
                left: 15px;
                color: #6c757d;
            }
            
            .btn-login {
                background: var(--primary-gradient);
                border: none;
                border-radius: 50px;
                padding: 12px;
                font-weight: 600;
                transition: all 0.3s;
            }
            
            .btn-login:hover {
                background: var(--secondary-gradient);
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
            
            .brand-text {
                font-weight: 700;
                letter-spacing: 1px;
                color: #fff;
                text-shadow: 0 2px 4px rgba(0,0,0,0.2);
            }
        </style>
    </head>
    <body>
        <div class="login-container">
            <div class="login-card">
                <div class="card-header">
                    <h2 class="mb-0"><i class="fas fa-lock me-2"></i>ACESSO RESTRITO</h2>
                    <p class="mb-0 mt-2">Sistema de Gestão de Clientes</p>
                </div>
                
                <div class="card-body">
                    <?php if (!empty($erro)): ?>
                        <div class="alert alert-danger text-center"><?= htmlspecialchars($erro) ?></div>
                    <?php endif; ?>
                    <form method="post">
                        <div class="mb-4 position-relative">
                            <div class="position-relative">
                                <span class="input-icon">
                                    <i class="fas fa-key"></i>
                                </span>
                                <input 
                                    type="password" 
                                    name="codigo" 
                                    id="codigo" 
                                    class="form-control ps-5" 
                                    placeholder="Código de acesso" 
                                    required
                                    autofocus
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
            <div class="text-center mt-4">
                <p class="text-white mb-0">© <?= date('Y') ?> Sistema de Gestão - Todos os direitos reservados</p>
            </div>
        </div>

        <script>
            // Toggle para mostrar/esconder senha
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#codigo');
            
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });
        </script>
    </body>
    </html>
    <?php
    $content = ob_get_clean();
    echo $content;
    exit;
}
?>

<!-- Dashboard -->
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0"><i class="fas fa-tachometer-alt me-2 text-primary"></i>Dashboard de Clientes</h1>
    
    </div>

    <div class="row mb-4 g-3">
        <?php
        // Função para gerar cards de forma dinâmica
        function dashboardCard($icon, $color, $title, $value, $formatted = true, $isCurrency = true) {
            $formattedValue = $formatted ? 
                ($isCurrency ? 'R$ ' . number_format($value, 2, ',', '.') : $value) : 
                $value;
                
            return '
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="bg-' . $color . '-subtle p-3 rounded-circle">
                                <i class="fas ' . $icon . ' fa-2x text-' . $color . '"></i>
                            </div>
                            <div class="text-end">
                                <h2 class="fw-bold mb-0 text-' . $color . '">' . $formattedValue . '</h2>
                                <p class="text-muted mb-0">' . $title . '</p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-' . $color . '" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        }

        // Calculando valores para os cards
        $totalClientes = count($clients);
        $aprovados = count(array_filter($clients, fn($c) => $c['approval_status'] === 'Aprovado'));
        $totalFinanciado = array_sum(array_column($clients, 'financed_value'));
        $totalImoveis = array_sum(array_column($clients, 'property_value'));
        $totalSubsidio = array_sum(array_column($clients, 'subsidy_value'));
        $mediaFinanciada = $totalClientes ? $totalFinanciado / $totalClientes : 0;
        $mediaImovel = $totalClientes ? $totalImoveis / $totalClientes : 0;
        $comObservacao = count(array_filter($clients, fn($c) => !empty($c['observation'])));

        // Gerando os cards
        echo dashboardCard('fa-users', 'primary', 'Total de Clientes', $totalClientes, true, false);
        echo dashboardCard('fa-user-check', 'success', 'Aprovados', $aprovados, true, false);
        echo dashboardCard('fa-hand-holding-usd', 'info', 'Total Financiado', $totalFinanciado);
        echo dashboardCard('fa-home', 'warning', 'Valor dos Imóveis', $totalImoveis);
        echo dashboardCard('fa-gift', 'success', 'Total de Subsídio', $totalSubsidio);
        echo dashboardCard('fa-chart-line', 'secondary', 'Média Financiada', $mediaFinanciada);
        echo dashboardCard('fa-building', 'primary', 'Média Imóvel', $mediaImovel);
        echo dashboardCard('fa-comment-dots', 'dark', 'Com Observação', $comObservacao, true, false);
        ?>
    </div>

    <!-- Seção de gráficos (exemplo) -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Status de Aprovação</h5>
                </div>
                <div class="card-body">
                    <canvas id="approvalChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Valores por Mês</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>