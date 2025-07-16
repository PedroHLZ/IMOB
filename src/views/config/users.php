<?php
ob_start();
?>
<h1>Gerenciar Usuários/Códigos de Acesso</h1>

<form method="POST" action="/config/add-user" class="row g-2 mb-4">
    <div class="col-md-3">
        <input type="text" name="name" class="form-control" placeholder="Nome do usuário" required>
    </div>
    <div class="col-md-3">
        <input type="text" name="code" class="form-control" placeholder="Código de acesso" required>
    </div>
    <div class="col-md-2">
        <select name="level" class="form-select" required>
            <option value="corretor">Corretor</option>
            <option value="admin">Administrador</option>
        </select>
    </div>
    <div class="col-md-3">
        <select name="broker_id" class="form-select">
            <option value="">-- Corretor vinculado --</option>
            <?php foreach ($brokers as $broker): ?>
                <option value="<?= $broker['id'] ?>"><?= htmlspecialchars($broker['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-1">
        <button type="submit" class="btn btn-primary w-100">Adicionar</button>
    </div>
</form>

<table class="table table-bordered table-hover">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Código</th>
            <th>Nível</th>
            <th>Corretor</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
<?php foreach ($users as $user): ?>
    <tr>
        <form method="POST" action="/config/edit-user" class="row g-1 align-items-center">
            <td><?= $user['id'] ?></td>
            <td><input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" class="form-control form-control-sm" required></td>
            <td><input type="text" name="code" value="<?= htmlspecialchars($user['code']) ?>" class="form-control form-control-sm" required></td>
            <td>
                <select name="level" class="form-select form-select-sm" required>
                    <option value="corretor" <?= ($user['level'] ?? 'corretor')==='corretor'?'selected':'' ?>>Corretor</option>
                    <option value="admin" <?= ($user['level'] ?? '')==='admin'?'selected':'' ?>>Administrador</option>
                </select>
            </td>
            <td>
                <select name="broker_id" class="form-select form-select-sm">
                    <option value="">-- Corretor vinculado --</option>
                    <?php foreach ($brokers as $broker): ?>
                        <option value="<?= $broker['id'] ?>" <?= ($user['broker_id'] ?? '')==$broker['id']?'selected':'' ?>><?= htmlspecialchars($broker['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td style="white-space:nowrap">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                <button type="submit" class="btn btn-sm btn-success">Salvar</button>
                <form method="POST" action="/config/delete-user" style="display:inline-block">
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-danger ms-1" onclick="return confirm('Excluir este usuário?')">Excluir</button>
                </form>
            </td>
        </form>
    </tr>
<?php endforeach; ?>
    </tbody>
</table>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
