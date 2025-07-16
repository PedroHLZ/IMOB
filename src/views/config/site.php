<?php ob_start(); ?>
<h1>Configuração do Site</h1>
<form method="POST" action="/config/update-site">
    <div class="mb-3">
        <label for="company" class="form-label">Nome da Empresa</label>
        <input type="text" class="form-control" id="company" name="company" value="<?= htmlspecialchars($config['company'] ?? '') ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
</form>

<h2 class="mt-5">Corretores</h2>
<a href="/config/brokers" class="btn btn-secondary mb-3">Gerenciar Corretores</a>
<a href="/config/leads" class="btn btn-outline-primary mb-3 ms-2"><i class="fas fa-cogs me-1"></i>Configuração de Distribuição de Leads</a>
<a href="/config/users" class="btn btn-outline-secondary mb-3 ms-2"><i class="fas fa-users-cog me-1"></i>Usuários/Acesso</a>
<?php $content = ob_get_clean(); include __DIR__ . '/../layout.php'; ?>
