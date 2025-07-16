<?php
// Página: Meus Clientes (para corretores)
ob_start();
?>
<h1 class="mb-4">Meus Clientes</h1>
<?php if (empty($clients)): ?>
    <div class="alert alert-info">Nenhum cliente encontrado para você.</div>
<?php else: ?>
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>CPF</th>
                <th>Telefone</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($clients as $client): ?>
            <tr>
                <td><?= $client['id'] ?></td>
                <td><?= htmlspecialchars($client['name']) ?></td>
                <td><?= htmlspecialchars($client['cpf']) ?></td>
                <td><?= htmlspecialchars($client['phone']) ?></td>
                <td><?= htmlspecialchars($client['approval_status']) ?></td>
                <td>
                    <a href="/clients/show?id=<?= $client['id'] ?>" class="btn btn-sm btn-primary">Ver</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
