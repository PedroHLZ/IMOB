
<?php
// Página: Editar Lead
ob_start();
?>
<h1 class="mb-4"><i class="fas fa-edit me-2"></i>Editar Lead</h1>

<form method="post" action="/leads/edit?id=<?= $lead['id'] ?>" class="card shadow-sm p-4 mx-auto" style="max-width:500px;">
    <div class="mb-3">
        <label for="name" class="form-label">Nome do Lead</label>
        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($lead['name']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($lead['email']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Telefone</label>
        <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($lead['phone']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="city" class="form-label">Cidade</label>
        <input type="text" class="form-control" id="city" name="city" value="<?= htmlspecialchars($lead['city'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label for="neighborhood" class="form-label">Bairro</label>
        <input type="text" class="form-control" id="neighborhood" name="neighborhood" value="<?= htmlspecialchars($lead['neighborhood'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label for="obs" class="form-label">Observação</label>
        <textarea class="form-control" id="obs" name="obs" rows="2"><?= htmlspecialchars($lead['obs'] ?? '') ?></textarea>
    </div>
    <div class="mb-3">
        <button type="submit" class="btn btn-warning w-100 mb-2"><i class="fas fa-save me-2"></i>Salvar Alterações</button>
        <button type="submit" name="aprovar_lead" value="1" class="btn btn-success w-100"><i class="fas fa-check me-2"></i>Aprovar e virar Cliente</button>
    </div>
</form>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
