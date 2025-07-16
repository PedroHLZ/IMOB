<?php
// Página: Cadastro de Leads
ob_start();
?>
<h1 class="mb-4"><i class="fas fa-user-plus me-2"></i>Cadastrar Lead</h1>

<form method="post" action="/leads/create" class="card shadow-sm p-4 mx-auto" style="max-width:500px;">
    <div class="mb-3">
        <label for="name" class="form-label">Nome do Lead</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Telefone</label>
        <input type="text" class="form-control" id="phone" name="phone" required>
    </div>
    <div class="mb-3">
        <label for="city" class="form-label">Cidade</label>
        <input type="text" class="form-control" id="city" name="city" placeholder="Ex: Paulista">
    </div>
    <div class="mb-3">
        <label for="neighborhood" class="form-label">Bairro</label>
        <input type="text" class="form-control" id="neighborhood" name="neighborhood" placeholder="Ex: Janga">
    </div>
    <div class="mb-3">
        <label for="obs" class="form-label">Observação</label>
        <textarea class="form-control" id="obs" name="obs" rows="2"></textarea>
    </div>
    <button type="submit" class="btn btn-success w-100"><i class="fas fa-save me-2"></i>Salvar Lead</button>
</form>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
