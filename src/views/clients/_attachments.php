<?php
$uploadDir = __DIR__ . '/../../../public/uploads/cliente_' . $client['id'];
$files = [];
if (is_dir($uploadDir)) {
    $files = array_diff(scandir($uploadDir), ['.', '..']);
}
?>

<div class="mb-4">
    <h5 class="fw-bold mb-3 text-primary d-flex align-items-center">
        <i class="fas fa-paperclip me-2"></i> Anexos do Cliente
        <span class="badge bg-primary ms-2"><?= count($files) ?></span>
    </h5>
    
    <div class="d-flex flex-wrap gap-3">
        <?php if (empty($files)): ?>
            <div class="alert alert-info w-100 d-flex align-items-center">
                <i class="fas fa-info-circle me-2"></i> Nenhum anexo enviado para este cliente
            </div>
        <?php else: ?>
            <?php foreach ($files as $file): ?>
                <?php
                    $url = "/uploads/cliente_{$client['id']}/" . rawurlencode($file);
                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);
                    $fileSize = filesize($uploadDir . '/' . $file);
                    $fileSizeFormatted = $fileSize > 1024 ? round($fileSize/1024, 1).' KB' : $fileSize.' bytes';
                ?>
                <div class="attachment-card position-relative">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-2 text-center">
                            <a href="<?= $url ?>" target="_blank" class="text-decoration-none">
                                <?php if ($isImage): ?>
                                    <div class="img-container mb-2" style="height: 100px; overflow: hidden; border-radius: 6px;">
                                        <img src="<?= $url ?>" alt="<?= htmlspecialchars($file) ?>" class="img-fluid" style="object-fit: cover; height: 100%; width: 100%;">
                                    </div>
                                <?php else: ?>
                                    <div class="file-icon mb-2" style="height: 100px; display: flex; align-items: center; justify-content: center;">
                                        <?php if ($ext === 'pdf'): ?>
                                            <i class="fas fa-file-pdf text-danger" style="font-size: 3rem;"></i>
                                        <?php elseif ($ext === 'doc' || $ext === 'docx'): ?>
                                            <i class="fas fa-file-word text-primary" style="font-size: 3rem;"></i>
                                        <?php elseif ($ext === 'xls' || $ext === 'xlsx'): ?>
                                            <i class="fas fa-file-excel text-success" style="font-size: 3rem;"></i>
                                        <?php else: ?>
                                            <i class="fas fa-file text-secondary" style="font-size: 3rem;"></i>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="file-info">
                                    <div class="file-name fw-medium text-truncate mb-1" title="<?= htmlspecialchars($file) ?>">
                                        <?= htmlspecialchars($file) ?>
                                    </div>
                                    <div class="file-size text-muted small">
                                        <?= $fileSizeFormatted ?>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    
                    <div class="attachment-actions position-absolute top-0 end-0 mt-1 me-1">
                        <a href="<?= $url ?>" download="<?= htmlspecialchars($file) ?>" class="btn btn-sm btn-light text-primary" title="Baixar">
                            <i class="fas fa-download"></i>
                        </a>
                        <form method="POST" action="/clients/delete-attachment" class="d-inline">
                            <input type="hidden" name="client_id" value="<?= $client['id'] ?>">
                            <input type="hidden" name="file" value="<?= htmlspecialchars($file) ?>">
                            <button type="submit" class="btn btn-sm btn-light text-danger" title="Excluir" onclick="return confirmDelete(event)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <style>
        .attachment-card {
            width: 150px;
            transition: transform 0.3s ease;
        }
        
        .attachment-card:hover {
            transform: translateY(-5px);
        }
        
        .attachment-card .card {
            border-radius: 10px;
            overflow: hidden;
            transition: box-shadow 0.3s ease;
        }
        
        .attachment-card:hover .card {
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }
        
        .attachment-actions {
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .attachment-card:hover .attachment-actions {
            opacity: 1;
        }
        
        .attachment-actions .btn {
            padding: 4px 8px;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-left: 2px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
    </style>
    
    <script>
        function confirmDelete(event) {
            event.preventDefault();
            const form = event.target.closest('form');
            
            Swal.fire({
                title: 'Confirmar exclusão',
                text: "Tem certeza que deseja excluir este anexo?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            
            return false;
        }
    </script>
</div>