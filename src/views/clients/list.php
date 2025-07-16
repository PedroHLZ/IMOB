<?php
ob_start();
?>


<div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="mb-0"><i class="fas fa-users me-2"></i>Lista de Clientes</h1>
    <span class="badge bg-primary fs-6">Total: <?= count($clients) ?></span>
</div>


<div class="d-flex flex-wrap gap-2 mb-4 align-items-center">
    <a href="/clients/create" class="btn btn-success shadow-sm">
        <i class="fas fa-plus me-2"></i> Novo Cliente
    </a>
    <a href="/clients/pdf-list" class="btn btn-outline-danger shadow-sm">
        <i class="fas fa-file-pdf me-2"></i>PDF da Lista
    </a>
    <form class="d-inline-block ms-auto" method="get" action="/listaclientes">
        <div class="input-group">
            <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" class="form-control" style="max-width:200px;" placeholder="Buscar por nome ou CPF">
            <button class="btn btn-outline-secondary" type="submit" title="Buscar"><i class="fas fa-search"></i></button>
        </div>
    </form>
</div>


<div class="table-responsive rounded shadow-sm border">
    <table class="table table-hover align-middle mb-0" style="min-width:900px;">
        <thead class="table-dark sticky-top" style="z-index:1;">
            <tr>
                <th class="text-center">ID</th>
                <th>Nome</th>
                <th>CPF</th>
                <th>Corretor</th>
                <th>Status</th>
                <th>Observação</th>
                <th class="text-end">Subsídio (R$)</th>
                <th class="text-end">Financiamento (R$)</th>
                <th class="text-end">Valor Total (R$)</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
<?php
$grouped = [];
foreach ($clients as $client) {
    $broker = $client['broker'] ?: 'Sem Corretor';
    $grouped[$broker][] = $client;
}
ksort($grouped);
?>
<?php foreach ($grouped as $broker => $brokerClients): ?>
    <tr class="table-primary">
        <td colspan="10" class="fw-bold fs-5 text-primary"><i class="fas fa-user-tie me-2"></i><?= htmlspecialchars($broker) ?></td>
    </tr>
    <?php foreach ($brokerClients as $client): ?>
        <tr class="align-middle">
            <td class="text-center fw-bold text-secondary small">#<?= $client['id'] ?></td>
            <td class="fw-semibold text-dark"><?= htmlspecialchars($client['name']) ?></td>
            <td class="text-monospace text-muted small"><?= htmlspecialchars($client['cpf']) ?></td>
            <td><span class="badge bg-secondary px-2 py-1"><?= htmlspecialchars($client['broker']) ?></span></td>
            <td>
                <?php
                $statusClass = match ($client['approval_status']) {
                    'Aprovado' => 'success',
                    'Condicionado' => 'warning',
                    'Reprovado' => 'danger',
                    default => 'info',
                };
                ?>
                <span class="badge bg-<?= $statusClass ?> px-2 py-2 fs-6 shadow-sm"><?= htmlspecialchars($client['approval_status']) ?></span>
            </td>
            <td class="text-muted small"><?= $client['observation'] ?: '-' ?></td>
            <td class="text-end">R$ <span class="text-success fw-bold"><?= number_format((float)($client['subsidy_value'] ?? 0), 2, ',', '.') ?></span></td>
            <td class="text-end">R$ <span class="text-primary fw-bold"><?= number_format((float)($client['financed_value'] ?? 0), 2, ',', '.') ?></span></td>
            <td class="text-end"><strong>R$ <?= number_format((float)($client['subsidy_value'] ?? 0) + (float)($client['financed_value'] ?? 0), 2, ',', '.') ?></strong></td>
            <td class="text-center">
                <div class="btn-group btn-group-sm" role="group">
                    <a href="/clients/show?id=<?= $client['id'] ?>" class="btn btn-outline-info" title="Ver">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="/clients/edit?id=<?= $client['id'] ?>" class="btn btn-outline-warning" title="Editar">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="/clients/delete?id=<?= $client['id'] ?>" class="btn btn-outline-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir?')">
                        <i class="fas fa-trash"></i>
                    </a>
                    <a href="/clients/pdf?id=<?= $client['id'] ?>" class="btn btn-outline-success" title="Gerar PDF">
                        <i class="fas fa-file-pdf"></i>
                    </a>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
<?php endforeach; ?>
        </tbody>
    </table>
</div>



<style>
    .table-hover tbody tr:hover {
        background-color: #f1f7ff !important;
        transition: background 0.2s;
    }
    .table thead th {
        vertical-align: middle;
    }
    .table td, .table th {
        vertical-align: middle;
        padding-top: 0.55rem;
        padding-bottom: 0.55rem;
    }
    .table-responsive {
        background: #fff;
    }
    .btn-group .btn {
        min-width: 36px;
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
    .badge {
        letter-spacing: 0.02em;
    }
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
