<?php ob_start(); ?>
<h1>Gerenciar Corretores</h1>
<form method="POST" action="/config/add-broker" class="mb-4">
    <div class="row g-2 align-items-end">
        <div class="col-auto">
            <label for="name" class="form-label">Nome do Corretor</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success">Adicionar</button>
        </div>
    </div>
</form>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($brokers as $broker): ?>
            <tr>
                <td><?= $broker['id'] ?></td>
                <td><?= htmlspecialchars($broker['name']) ?></td>
                <td>
                    <a href="/config/delete-broker?id=<?= $broker['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Excluir corretor?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<a href="/config/site" class="btn btn-secondary mt-3">Voltar</a>
<?php $content = ob_get_clean(); include __DIR__ . '/../layout.php'; ?>
