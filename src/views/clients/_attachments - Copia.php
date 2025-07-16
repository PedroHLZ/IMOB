<?php
$uploadDir = __DIR__ . '/../../../public/uploads/cliente_' . $client['id'];
$files = [];
if (is_dir($uploadDir)) {
    $files = array_diff(scandir($uploadDir), ['.', '..']);
}
?>
<div class="mb-3">
    <label class="form-label">Anexos do Cliente</label>
    <div class="d-flex flex-wrap gap-3">
        <?php if (empty($files)): ?>
            <p class="text-muted">Nenhum anexo enviado.</p>
        <?php else: ?>
            <?php foreach ($files as $file): ?>
                <?php
                    $url = "/uploads/cliente_{$client['id']}/" . rawurlencode($file);
                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);
                ?>
                <div style="width:110px;text-align:center;position:relative;">
                      <form method="POST" action="/clients/delete-attachment" style="position:absolute;top:2px;right:2px;z-index:2;">
                        <input type="hidden" name="client_id" value="<?= $client['id'] ?>">
                        <input type="hidden" name="file" value="<?= htmlspecialchars($file) ?>">
                        <button type="submit" class="btn btn-sm btn-danger" style="padding:2px 6px;font-size:12px;line-height:1;" title="Excluir anexo" onclick="return confirm('Excluir este anexo?')">
                            <i class="fas fa-times"></i>
                        </button>
                    </form>
                    <a href="<?= $url ?>" target="_blank" style="text-decoration:none;">
                        <?php if ($isImage): ?>
                            <img src="<?= $url ?>" alt="<?= htmlspecialchars($file) ?>" style="max-width:100px;max-height:100px;border:1px solid #ccc;border-radius:6px;display:block;margin:0 auto 5px;">
                        <?php elseif ($ext === 'pdf'): ?>
                            <span style="font-size:48px;color:#d32f2f;"><i class="fas fa-file-pdf"></i></span>
                        <?php else: ?>
                            <span style="font-size:48px;color:#888;"><i class="fas fa-file"></i></span>
                        <?php endif; ?>
                        <div style="font-size:12px;word-break:break-all;max-width:100px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;">
                            <?= htmlspecialchars($file) ?>
                        </div>
                    </a>
                  
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
