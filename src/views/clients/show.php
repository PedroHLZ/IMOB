<?php
ob_start();
$userLevel = $_SESSION['user_level'] ?? null;
?>

<h1 class="mb-4">Detalhes do Cliente</h1>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><?= htmlspecialchars($client['name']) ?></h5>
        <h6 class="card-subtitle mb-2 text-muted">CPF: <?= htmlspecialchars($client['cpf']) ?></h6>

        <div class="row mt-4">
            <div class="col-md-6">
                <p><strong>Telefone:</strong> <?= htmlspecialchars($client['phone']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($client['email']) ?></p>
                <p><strong>Data de Nascimento:</strong> <?= htmlspecialchars($client['birthdate']) ?></p>
                <p><strong>Estado Civil:</strong> <?= htmlspecialchars($client['civil_status']) ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Corretor:</strong> <span class="badge bg-secondary"><?= htmlspecialchars($client['broker']) ?></span></p>
                <p><strong>Renda Formal:</strong> R$ <?= number_format((float)($client['formal_income'] ?? 0), 2, ',', '.') ?></p>
                <p><strong>Renda Informal:</strong> R$ <?= number_format((float)($client['informal_income'] ?? 0), 2, ',', '.') ?></p>
                <p><strong>Status de Aprovação:</strong>
                    <?php
                    $statusClass = '';
                    if ($client['approval_status'] == 'Aprovado') {
                        $statusClass = 'success';
                    } elseif ($client['approval_status'] == 'Condicionado') {
                        $statusClass = 'warning';
                    } elseif ($client['approval_status'] == 'Reprovado') {
                        $statusClass = 'danger';
                    } else {
                        $statusClass = 'info';
                    }
                    ?>
                    <span class="badge bg-<?= $statusClass ?>"><?= htmlspecialchars($client['approval_status']) ?></span>
                </p>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-4">
                <p><strong>Valor do Imóvel:</strong> R$ <?= number_format((float)($client['property_value'] ?? 0), 2, ',', '.') ?></p>
            </div>
            <div class="col-md-4">
                <p><strong>Valor Financiado:</strong> R$ <?= number_format((float)($client['financed_value'] ?? 0), 2, ',', '.') ?></p>
            </div>
            <div class="col-md-4">
                <p><strong>Valor Subsídio:</strong> R$ <?= number_format((float)($client['subsidy_value'] ?? 0), 2, ',', '.') ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <p><strong>Total Geral:</strong> R$ <?= number_format((float)($client['total_value'] ?? 0), 2, ',', '.') ?></p>
            </div>
        </div>

        <!-- Seção Dependente -->
        <hr>
        <h4>Dados do Dependente</h4>
        <div class="row mt-3">
            <div class="col-md-6">
                <p><strong>Nome:</strong> <?= htmlspecialchars($client['dependent_name'] ?? '-') ?></p>
                <p><strong>CPF:</strong> <?= htmlspecialchars($client['dependent_cpf'] ?? '-') ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Data de Nascimento:</strong> <?= htmlspecialchars($client['dependent_birthdate'] ?? '-') ?></p>
                <p><strong>Parentesco:</strong> <?= htmlspecialchars($client['dependent_relationship'] ?? '-') ?></p>
            </div>
        </div>
        <h4>Informações Adicionais</h4>
        <p><strong>Programa MCMV:</strong> <?= htmlspecialchars($client['program_mcmv'] ?? '—') ?></p>
        <p><strong>FGTS:</strong> <?= htmlspecialchars($client['fgts'] ?? '—') ?></p>

        <!-- Observação -->
        <hr>
        <h4>Observação</h4>
        <p><?= nl2br(htmlspecialchars($client['observation'])) ?: '-' ?></p>

        <hr>
        <h4>Anexos</h4>
        <?php include __DIR__ . '/_attachments.php'; ?>

        <?php if ($userLevel === 'admin'): ?>
            <!-- Botões de ação -->
            <hr>
            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="/clients" class="btn btn-secondary">Voltar</a>
                <a href="/clients/edit?id=<?= $client['id'] ?>" class="btn btn-warning">Editar</a>
                <a href="/clients/pdf?id=<?= $client['id'] ?>" class="btn btn-success">
                    <i class="fas fa-file-pdf me-1"></i> Gerar PDF
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>