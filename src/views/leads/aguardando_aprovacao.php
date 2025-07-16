<?php
// Página: Leads aguardando aprovação
ob_start();
?>
<h1 class="mb-4"><i class="fas fa-hourglass-half me-2"></i>Leads Aguardando Aprovação</h1>

<?php if (empty($leads)): ?>
    <div class="alert alert-info">Nenhum lead aguardando aprovação.</div>
<?php else: ?>
    <div class="table-responsive rounded shadow-sm border">
        <table class="table table-hover align-middle mb-0" style="min-width:700px;">
            <thead class="table-dark sticky-top">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>Cidade</th>
                    <th>Bairro</th>
                    <th>Status</th>
                    <th>Corretor</th>
                    <th>Data</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leads as $lead): ?>
                    <tr>
                        <td class="fw-bold text-secondary small">#<?= $lead['id'] ?></td>
                        <td><?= htmlspecialchars($lead['name']) ?></td>
                        <td><?= htmlspecialchars($lead['email']) ?></td>
                        <td><?= htmlspecialchars($lead['phone']) ?></td>
                        <td><?= htmlspecialchars($lead['city'] ?? '') ?></td>
                        <td><?= htmlspecialchars($lead['neighborhood'] ?? '') ?></td>
                        <td><span class="badge bg-warning text-dark px-2 py-2 fs-6 shadow-sm">Aguardando Aprovação</span></td>
                        <td>
                            <?php if ($lead['assigned_broker_name'] ?? null): ?>
                                <span class="badge bg-info text-dark px-2 py-1"><?= htmlspecialchars($lead['assigned_broker_name']) ?></span>
                            <?php else: ?>
                                <span class="text-muted small">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted small"><?= date('d/m/Y H:i', strtotime($lead['created_at'])) ?></td>
                        <td class="text-center">
                            <a href="/leads/edit?id=<?= $lead['id'] ?>" class="btn btn-sm btn-outline-warning" title="Editar"><i class="fas fa-edit"></i></a>
                            <a href="/leads/delete?id=<?= $lead['id'] ?>" class="btn btn-sm btn-outline-danger" title="Excluir" onclick="return confirm('Excluir este lead?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<style>
.table-hover tbody tr:hover {
    background-color: #f1f7ff !important;
    transition: background 0.2s;
}
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
