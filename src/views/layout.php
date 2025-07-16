<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pernambuco Imobiliária</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-color: #1a2a6c;
            --secondary-color: #1c92afff;
            --accent-color: #1c92afff;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --sidebar-width: 250px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f0f4f8;
            color: var(--dark-color);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Sidebar Estilizada */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            padding-bottom: 40px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
        }

        .sidebar-header img {
            height: 38px;
            border-radius: 8px;
            margin-right: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .sidebar-header span {
            color: white;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .sidebar-category {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 10px 20px;
            margin-top: 10px;
        }

        .sidebar-link {
            display: block;
            color: rgba(255, 255, 255, 0.8);
            padding: 10px 20px;
            text-decoration: none;
            margin: 0 10px 5px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s;
            display: flex;
            align-items: center;
        }

        .sidebar-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }

        .sidebar-user {
            background: rgba(0, 0, 0, 0.15);
            border-radius: 6px;
            padding: 12px 15px;
            margin: 15px;
            color: white;
            display: flex;
            align-items: center;
        }

        .sidebar-user i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .sidebar-user .btn-link {
            color: rgba(255, 255, 255, 0.7);
            padding: 0;
            margin-left: 10px;
            font-size: 0.9rem;
        }

        .sidebar-user .btn-link:hover {
            color: white;
            text-decoration: none;
        }

        .container.main-content {
            margin-left: var(--sidebar-width);
            padding: 30px;
            transition: all 0.3s ease;
            width: calc(100% - var(--sidebar-width));
        }

        /* Botão toggle para mobile */
        #toggleSidebar {
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1050;
            display: none;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Conteúdo principal */
        .page-content {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            padding: 25px;
            min-height: calc(100vh - 60px);
        }

        /* Animações */
        .animate__animated {
            animation-duration: 0.5s;
        }

        /* Responsividade */
        @media (max-width: 991px) {
            .sidebar {
                left: -260px;
            }

            body.sidebar-open .sidebar {
                left: 0;
            }

            .container.main-content {
                margin-left: 0 !important;
                width: 100%;
            }

            #toggleSidebar {
                display: flex;
            }
        }

        /* Cards modernos */
        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            font-weight: 600;
            border-bottom: none;
            padding: 15px 20px;
        }

        /* Botões */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        /* Formulários */
        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(28, 146, 175, 0.25);
        }

        /* Estilos para a área do usuário */
        .sidebar-user {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            padding: 12px 15px;
            margin: 15px 10px;
            transition: all 0.3s ease;
            display: flex;
            align-items: flex-start;
        }

        .sidebar-user:hover {
            background: rgba(0, 0, 0, 0.25);
            transform: translateY(-2px);
        }

        .sidebar-user .btn-link {
            color: rgba(255, 255, 255, 0.7) !important;
            transition: all 0.2s;
        }

        .sidebar-user .btn-link:hover {
            color: white !important;
            transform: scale(1.1);
        }

        .sidebar-user .badge {
           font-size: 0.75rem;
    padding: 4px 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    margin: 0 0 0 20px;
        }
    </style>
</head>

<body>
    <?php
    // Garante que $userLevel está definido para evitar warnings
    $userLevel = $_SESSION['user_level'] ?? null;

    // Função para marcar item ativo
    if (!function_exists('navActive')) {
        function navActive($href)
        {
            $uri = $_SERVER['REQUEST_URI'] ?? '/';
            return strpos($uri, $href) === 0 ? 'active' : '';
        }
    }
    ?>



    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="/logo2.jpg" alt="Logo">
            <span><?= htmlspecialchars($config['company'] ?? 'Pernambuco Imobiliária') ?></span>
        </div>

        <ul class="list-unstyled sidebar-menu">
            <?php if (isset($_SESSION['usuario'])): ?>
                <li class="sidebar-category">Usuário</li>
                <li class="sidebar-user">

                    <div class="d-flex flex-column w-100">
                        <div class="d-flex align-items-left">
                              <i class="fas fa-user-circle me-2"></i>
                                <span class="fw-medium text-truncate"><?= htmlspecialchars($_SESSION['usuario']) ?></span>
                               <span class="badge bg-light text-primary rounded-pill fw-normal me-2">
                                    <?= htmlspecialchars($_SESSION['user_level'] ?? 'corretor') ?>
                                </span>
                                <div class="ms-auto">
                                <form method="post" action="/logout" class="d-inline">
                                    <button type="submit" class="btn btn-link p-0 text-white text-decoration-none" title="Sair">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endif; ?>

            <li class="sidebar-category">Geral</li>
            <li><a class="sidebar-link <?= navActive('/') ?>" href="/"><i class="fas fa-home"></i> Início</a></li>

            <?php if ($userLevel === 'admin'): ?>
                <li class="sidebar-category">Clientes</li>
                <li><a class="sidebar-link <?= navActive('/listaclientes') ?>" href="/listaclientes"><i class="fas fa-users"></i> Lista de Clientes</a></li>
                <li><a class="sidebar-link <?= navActive('/clients/create') ?>" href="/clients/create"><i class="fas fa-user-plus"></i> Cadastrar Cliente</a></li>

                <li class="sidebar-category">Leads</li>
                <li><a class="sidebar-link <?= navActive('/leads/list') ?>" href="/leads/list"><i class="fas fa-list"></i> Lista de Leads</a></li>
                <li><a class="sidebar-link <?= navActive('/leads/aguardando-aprovacao') ?>" href="/leads/aguardando-aprovacao"><i class="fas fa-hourglass-half"></i> Leads Aguardando Aprovação</a></li>
                <li><a class="sidebar-link <?= navActive('/leads/create') ?>" href="/leads/create"><i class="fas fa-plus"></i> Cadastrar Lead</a></li>
                <li><a class="sidebar-link <?= navActive('/leads/import-csv') ?>" href="/leads/import-csv"><i class="fas fa-file-csv"></i> Importar Leads (CSV)</a></li>
                <li><a class="sidebar-link" href="#" id="distribuirLeadsBtn"><i class="fas fa-random"></i> Distribuir Leads</a></li>

                <li class="sidebar-category">Configurações</li>
                <li><a class="sidebar-link" href="/config/site"><i class="fas fa-cog"></i> Site</a></li>
            <?php endif; ?>

            <?php if ($userLevel === 'corretor' || $userLevel === null): ?>
                <li class="sidebar-category">Clientes</li>
                <li><a class="sidebar-link <?= navActive('/meus-clientes') ?>" href="/meus-clientes"><i class="fas fa-users"></i> Meus Clientes</a></li>

                <li class="sidebar-category">Leads</li>
                <li><a class="sidebar-link <?= navActive('/meus-leads') ?>" href="/meus-leads"><i class="fas fa-list"></i> Meus Leads</a></li>
            <?php endif; ?>
        </ul>
    </aside> <!-- Botão hamburguer para abrir a sidebar -->
    <!-- Botão hamburguer para abrir a sidebar - visível apenas em mobile -->
    <button id="toggleSidebar" class="btn btn-primary d-lg-none position-fixed" style="top: 10px; left: 80%; z-index: 1050;">
        <i class="fas fa-bars"></i>
    </button>

    <div class="container main-content">
        <!-- Alertas via SweetAlert2 -->
        <?php if (isset($_SESSION['success'])): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso',
                        text: "<?= htmlspecialchars($_SESSION['success'], ENT_QUOTES) ?>"
                    });
                });
            </script>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: "<?= htmlspecialchars($_SESSION['error'], ENT_QUOTES) ?>"
                    });
                });
            </script>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['info'])): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'info',
                        title: 'Atenção',
                        text: "<?= htmlspecialchars($_SESSION['info'], ENT_QUOTES) ?>"
                    });
                });
            </script>
            <?php unset($_SESSION['info']); ?>
        <?php endif; ?>

        <!-- Conteúdo Principal -->
        <div class="page-content animate__animated animate__fadeInUp">
            <?= $content ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Alternar a sidebar em dispositivos móveis
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.body.classList.toggle('sidebar-open');
        });

        // Distribuição de Leads
        document.getElementById('distribuirLeadsBtn')?.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Deseja distribuir os leads agora?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1c92afff',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sim, distribuir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/leads/distribuir';
                }
            });
        });

        // Substituir confirmações padrão por SweetAlert2
        document.querySelectorAll('button, a').forEach(function(el) {
            if (el.hasAttribute('onclick') && el.getAttribute('onclick').includes('confirm(')) {
                var original = el.getAttribute('onclick');
                el.onclick = function(ev) {
                    ev.preventDefault();
                    var msg = original.match(/confirm\(['"](.+?)['"]\)/);
                    if (msg && msg[1]) {
                        Swal.fire({
                            title: msg[1],
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Sim',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                if (el.tagName === 'A') {
                                    window.location.href = el.getAttribute('href');
                                } else {
                                    el.closest('form').submit();
                                }
                            }
                        });
                    }
                    return false;
                };
            }
        });
    </script>
</body>

</html>