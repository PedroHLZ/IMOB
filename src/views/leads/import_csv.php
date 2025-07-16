<?php
// Página: Importar Leads via CSV
ob_start();
?>
<h1 class="mb-4"><i class="fas fa-file-csv me-2"></i>Importar Leads (CSV)</h1>

<div class="alert alert-info">
    O arquivo CSV deve conter as colunas: <strong>name, email, phone, city, neighborhood, obs</strong>.<br>
    Exemplo de cabeçalho: <code>name,email,phone,city,neighborhood,obs</code>
</div>

<form method="post" action="/leads/import-csv" enctype="multipart/form-data" class="card shadow-sm p-4 mx-auto" style="max-width:500px;">
    <div class="mb-3">
        <label for="csv" class="form-label">Arquivo CSV</label>
        <input type="file" class="form-control" id="csv" name="csv" accept=".csv" required>
    </div>
    <button type="submit" class="btn btn-success w-100"><i class="fas fa-upload me-2"></i>Importar Leads</button>
</form>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
