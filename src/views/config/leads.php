<?php
// Página de configuração de distribuição de leads
ob_start();
?>
<h1 class="mb-4"><i class="fas fa-cogs me-2"></i>Configuração de Distribuição de Leads</h1>

<form method="post" action="/config/update-leads" class="card shadow-sm p-4 mb-4" style="max-width:600px;">
    <div class="mb-3">
        <label for="leads_per_broker" class="form-label">Quantidade de leads a distribuir por corretor</label>
        <input type="number" min="1" class="form-control" id="leads_per_broker" name="leads_per_broker" value="<?= htmlspecialchars($config['leads_per_broker'] ?? 5) ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Corretores que receberão leads</label>
        <div class="border rounded p-2" style="max-height:220px;overflow-y:auto;">
            <?php foreach ($brokers as $broker): ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="brokers[]" value="<?= $broker['id'] ?>" id="broker_<?= $broker['id'] ?>"
                        <?= in_array($broker['id'], $selectedBrokers ?? []) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="broker_<?= $broker['id'] ?>">
                        <?= htmlspecialchars($broker['name']) ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Salvar Configurações</button>
</form>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
