

<?php


require_once __DIR__ . '/../../config/database.php';
echo '<!DOCTYPE html><html lang="pt-br"><head>';
echo '<meta charset="UTF-8">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '<title>Distribuição de Leads</title>';
echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">';
echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">';
echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>';
echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
echo '<style>body{background:linear-gradient(120deg,#f8fafc 60%,#e3e9f7 100%);} .card{border-radius:18px;} .table th,.table td{text-align:center;vertical-align:middle;} .icon-broker{color:#0d6efd;} .icon-lead{color:#198754;} .icon-total{color:#6c757d;} .btn-back{margin-top:30px;}</style>';
echo '</head><body>';
echo '<div class="container py-5">';
echo '<h2 class="mb-4 animate__animated animate__fadeInDown"><i class="fas fa-random me-2"></i>Distribuição de Leads</h2>';


$brokers = $pdo->query("SELECT id, name FROM brokers")->fetchAll();
if (!$brokers) {
    echo '<div class="alert alert-danger animate__animated animate__fadeInDown">Nenhum corretor cadastrado.</div>';
    echo '<a href="/leads/list" class="btn btn-secondary btn-back"><i class="fas fa-arrow-left me-1"></i>Voltar para lista de leads</a>';
    echo '</div></body></html>';
    exit;
}

$assigned = 0;
$summary = [];
foreach ($brokers as $broker) {
    $count = $pdo->query("SELECT COUNT(*) FROM leads WHERE assigned_broker_id = " . (int)$broker['id'])->fetchColumn();
    $toAssign = max(0, 5 - (int)$count);
    $qtdAntes = $count;
    $qtdDistribuidos = 0;
    if ($toAssign > 0) {
        $leads = $pdo->query("SELECT id FROM leads WHERE status = 'novo' AND assigned_broker_id IS NULL ORDER BY created_at ASC LIMIT $toAssign")->fetchAll();
        foreach ($leads as $lead) {
            $stmt = $pdo->prepare("UPDATE leads SET assigned_broker_id = :broker_id, status = 'distribuido' WHERE id = :lead_id");
            $stmt->execute([
                ':broker_id' => $broker['id'],
                ':lead_id' => $lead['id']
            ]);
            $assigned++;
            $qtdDistribuidos++;
        }
    }
    $summary[] = [
        'corretor' => $broker['name'],
        'antes' => $qtdAntes,
        'distribuidos' => $qtdDistribuidos,
        'total' => $qtdAntes + $qtdDistribuidos
    ];
}


echo '<div class="card shadow-sm mb-4 animate__animated animate__fadeInUp">';
echo '<div class="card-body">';
echo '<h5 class="card-title mb-3"><i class="fas fa-table me-2"></i>Resumo da Distribuição</h5>';
echo '<div class="table-responsive">';
echo '<table class="table table-bordered table-hover align-middle">';
echo '<thead class="table-light"><tr>';
echo '<th><i class="fas fa-user-tie icon-broker"></i> Corretor</th>';
echo '<th><i class="fas fa-inbox icon-lead"></i> Leads Antes</th>';
echo '<th><i class="fas fa-share-square icon-lead"></i> Distribuídos Agora</th>';
echo '<th><i class="fas fa-users icon-total"></i> Total Após</th>';
echo '</tr></thead><tbody>';
foreach ($summary as $row) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($row['corretor']) . '</td>';
    echo '<td>' . $row['antes'] . '</td>';
    echo '<td class="fw-bold text-success">' . $row['distribuidos'] . '</td>';
    echo '<td>' . $row['total'] . '</td>';
    echo '</tr>';
}
echo '</tbody></table>';
echo '</div>';
echo '</div></div>';


echo '<div class="text-center">';
if ($assigned > 0) {
    echo '<script>setTimeout(function(){Swal.fire({icon:"success",title:"Distribuição concluída!",text:"' . $assigned . ' lead(s) distribuído(s) com sucesso.",showConfirmButton:false,timer:2200,toast:false,position:"top"});}, 300);</script>';
    echo '<a href="/leads/list" class="btn btn-primary btn-lg animate__animated animate__pulse animate__infinite"><i class="fas fa-list me-1"></i>Ver lista de leads</a>';
} else {
    echo '<script>setTimeout(function(){Swal.fire({icon:"info",title:"Nada a distribuir!",text:"Nenhum lead novo disponível para distribuição.",showConfirmButton:false,timer:2200,toast:false,position:"top"});}, 300);</script>';
    echo '<a href="/leads/list" class="btn btn-secondary btn-lg"><i class="fas fa-arrow-left me-1"></i>Voltar para lista de leads</a>';
}
echo '</div>';
echo '</div></body></html>';

echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>';
echo '</html>';
?>