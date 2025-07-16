<?php
// Página: Meus Leads (para corretores)
ob_start();
?>
<h1 class="mb-4"><i class="fas fa-user-friends me-2"></i>Meus Leads</h1>


<?php if (empty($leads)): ?>
    <div class="alert alert-info">Nenhum lead foi distribuído para você ainda.</div>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($leads as $lead): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge bg-secondary me-2">#<?= $lead['id'] ?></span>
                            <span class="fw-bold fs-5 text-primary flex-grow-1"><?= htmlspecialchars($lead['name']) ?></span>
                        </div>
                        <div class="mb-2">
                            <i class="fas fa-envelope me-1"></i> <span class="text-muted small"> <?= htmlspecialchars($lead['email']) ?></span>
                        </div>
                        <div class="mb-2">
                            <i class="fas fa-phone me-1"></i> <span class="fw-semibold"> <?= htmlspecialchars($lead['phone']) ?></span>
                        </div>
                        <div class="mb-2">
                            <i class="fas fa-map-marker-alt me-1"></i> <?= htmlspecialchars($lead['city'] ?? '') ?><?= $lead['city'] && $lead['neighborhood'] ? ' - ' : '' ?><?= htmlspecialchars($lead['neighborhood'] ?? '') ?>
                        </div>
                        <form method="post" action="/meus-leads?lead_id=<?= $lead['id'] ?>" class="mb-2 d-flex align-items-center gap-2">
                            <label class="me-1 mb-0 small">Status:</label>
                            <select name="status" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                                <?php
                                $statusOptions = [
                                    'distribuido' => 'Distribuído',
                                    'respondeu' => 'Respondeu',
                                    'interessado' => 'Está interessado',
                                    'nao_responde' => 'Não responde',
                                    'nao_existe' => 'Não existe',
                                ];
                                foreach ($statusOptions as $value => $label):
                                ?>
                                    <option value="<?= $value ?>" <?= $lead['status'] === $value ? 'selected' : '' ?>><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                        <form method="post" action="/meus-leads?lead_id=<?= $lead['id'] ?>&enviar_admin=1" class="mb-2">
                            <button type="submit" class="btn btn-outline-dark btn-sm w-100" onclick="return confirm('Enviar este lead para aprovação do admin?')">
                                <i class="fas fa-share-square me-1"></i> Enviar para aprovação
                            </button>
                        </form>
                        <div class="mb-2 text-muted small">
                            Recebido em: <?= date('d/m/Y H:i', strtotime($lead['created_at'])) ?>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 d-flex gap-2 justify-content-end">
                        <a href="tel:<?= preg_replace('/\D/', '', $lead['phone']) ?>" class="btn btn-outline-primary btn-sm" title="Ligar">
                            <i class="fas fa-phone-alt"></i> Ligar
                        </a>
                        <a href="https://wa.me/55<?= preg_replace('/\D/', '', $lead['phone']) ?>" target="_blank" class="btn btn-success btn-sm" title="WhatsApp">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<style>
.card .badge {
    font-size: 1em;
}
.card .btn {
    min-width: 100px;
}
</style>

<style>
.table-hover tbody tr:hover {
    background-color: #f1f7ff !important;
    transition: background 0.2s;
}
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
