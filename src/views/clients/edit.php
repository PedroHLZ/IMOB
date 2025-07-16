<?php
ob_start();
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Editar Cliente</h1>
        <div>
            <a href="/clients" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> Voltar
            </a>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="card-title mb-0"><i class="fas fa-user me-2"></i> Informações Básicas</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="/clients/update" enctype="multipart/form-data" id="clientForm">
                <input type="hidden" name="id" value="<?= $client['id'] ?>">
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="<?= htmlspecialchars($client['name']) ?>" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="cpf" class="form-label">CPF <span class="text-danger">*</span></label>
                        <input type="text" class="form-control cpf-input" id="cpf" name="cpf"
                            value="<?= htmlspecialchars($client['cpf']) ?>" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="phone" class="form-label">Telefone</label>
                        <input type="text" class="form-control phone-input" id="phone" name="phone"
                            value="<?= htmlspecialchars($client['phone']) ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?= htmlspecialchars($client['email']) ?>">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="birthdate" class="form-label">Data de Nascimento</label>
                        <input type="date" class="form-control" id="birthdate" name="birthdate"
                            value="<?= $client['birthdate'] ?>">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="civil_status" class="form-label">Estado Civil</label>
                        <select class="form-select" id="civil_status" name="civil_status">
                            <option value="Solteiro(a)" <?= $client['civil_status'] == 'Solteiro(a)' ? 'selected' : '' ?>>Solteiro(a)</option>
                            <option value="Casado(a)" <?= $client['civil_status'] == 'Casado(a)' ? 'selected' : '' ?>>Casado(a)</option>
                            <option value="Divorciado(a)" <?= $client['civil_status'] == 'Divorciado(a)' ? 'selected' : '' ?>>Divorciado(a)</option>
                            <option value="Viúvo(a)" <?= $client['civil_status'] == 'Viúvo(a)' ? 'selected' : '' ?>>Viúvo(a)</option>
                            <option value="União Estável" <?= $client['civil_status'] == 'União Estável' ? 'selected' : '' ?>>União Estável</option>
                        </select>
                    </div>
                </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="card-title mb-0"><i class="fas fa-child me-2"></i> Dados do Dependente</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="dependent_name" class="form-label">Nome do Dependente</label>
                    <input type="text" class="form-control" id="dependent_name" name="dependent_name"
                        value="<?= htmlspecialchars($client['dependent_name']) ?>">
                </div>

                <div class="col-md-3 mb-3">
                    <label for="dependent_cpf" class="form-label">CPF do Dependente</label>
                    <input type="text" class="form-control cpf-input" id="dependent_cpf" name="dependent_cpf"
                        value="<?= htmlspecialchars($client['dependent_cpf']) ?>">
                </div>

                <div class="col-md-3 mb-3">
                    <label for="dependent_birthdate" class="form-label">Data de Nascimento</label>
                    <input type="date" class="form-control" id="dependent_birthdate" name="dependent_birthdate"
                        value="<?= $client['dependent_birthdate'] ?>">
                </div>
            </div>

            <div class="mb-3">
                <label for="dependent_relationship" class="form-label">Parentesco</label>
                <input type="text" class="form-control" id="dependent_relationship" name="dependent_relationship"
                    value="<?= htmlspecialchars($client['dependent_relationship']) ?>">
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="card-title mb-0"><i class="fas fa-money-bill-wave me-2"></i> Renda e Informações Financeiras</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="formal_income" class="form-label">Renda Formal (R$)</label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="text" class="form-control money-input" id="formal_income" name="formal_income"
                            value="<?= $client['formal_income'] ? number_format($client['formal_income'], 2, ',', '.') : '' ?>">
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="informal_income" class="form-label">Renda Informal (R$)</label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="text" class="form-control money-input" id="informal_income" name="informal_income"
                            value="<?= $client['informal_income'] ? number_format($client['informal_income'], 2, ',', '.') : '' ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="approval_status" class="form-label">Status de Aprovação</label>
                    <select class="form-select" id="approval_status" name="approval_status">
                        <option value="Em análise" <?= $client['approval_status'] == 'Em análise' ? 'selected' : '' ?>>Em análise</option>
                        <option value="Aprovado" <?= $client['approval_status'] == 'Aprovado' ? 'selected' : '' ?>>Aprovado</option>
                        <option value="Condicionado" <?= $client['approval_status'] == 'Condicionado' ? 'selected' : '' ?>>Condicionado</option>
                        <option value="Reprovado" <?= $client['approval_status'] == 'Reprovado' ? 'selected' : '' ?>>Reprovado</option>
                        <option value="Restrição" <?= $client['approval_status'] == 'Restrição' ? 'selected' : '' ?>>Restrição de Crédito</option>
                        <option value="Cartorio" <?= $client['approval_status'] == 'Cartorio' ? 'selected' : '' ?>>Cartório</option>
                        <option value="AssinaturaCaixa" <?= $client['approval_status'] == 'AssinaturaCaixa' ? 'selected' : '' ?>>Assinatura Caixa</option>
                        <option value="Visita" <?= $client['approval_status'] == 'Visita' ? 'selected' : '' ?>>Visita</option>
                        <option value="AssinaturaImobiliaria" <?= $client['approval_status'] == 'AssinaturaImobiliaria' ? 'selected' : '' ?>>Assinatura Imobiliaria</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="broker" class="form-label">Corretor Responsável</label>
                    <select class="form-select" id="broker" name="broker">
                        <?php foreach ($brokers as $broker): ?>
                            <option value="<?= htmlspecialchars($broker['name']) ?>" <?= ($client['broker'] == $broker['name']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($broker['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i> Informações Adicionais</h5>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Programa MCMV</label><br>
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="program_mcmv" id="program_mcmv_novo"
                                value="Novo" <?= ($client['program_mcmv'] ?? '') === 'Novo' ? 'checked' : '' ?>>
                            <label class="btn btn-outline-primary" for="program_mcmv_novo">Novo</label>

                            <input type="radio" class="btn-check" name="program_mcmv" id="program_mcmv_usado"
                                value="Usado" <?= ($client['program_mcmv'] ?? '') === 'Usado' ? 'checked' : '' ?>>
                            <label class="btn btn-outline-primary" for="program_mcmv_usado">Usado</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">FGTS</label><br>
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="fgts" id="fgts_sim"
                                value="Sim" <?= ($client['fgts'] ?? '') === 'Sim' ? 'checked' : '' ?>>
                            <label class="btn btn-outline-primary" for="fgts_sim">Sim</label>

                            <input type="radio" class="btn-check" name="fgts" id="fgts_nao"
                                value="Não" <?= ($client['fgts'] ?? '') === 'Não' ? 'checked' : '' ?>>
                            <label class="btn btn-outline-primary" for="fgts_nao">Não</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="observation" class="form-label">Observação</label>
                <textarea class="form-control" id="observation" name="observation" rows="3"><?= htmlspecialchars($client['observation']) ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="property_value" class="form-label">Valor do Imóvel (R$)</label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="text" class="form-control money-input" id="property_value" name="property_value"
                            value="<?= $client['property_value'] ? number_format($client['property_value'], 2, ',', '.') : '' ?>">
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="subsidy_value" class="form-label">Valor Subsídio (R$)</label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="text" class="form-control money-input" id="subsidy_value" name="subsidy_value"
                            value="<?= $client['subsidy_value'] ? number_format($client['subsidy_value'], 2, ',', '.') : '' ?>">
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="financed_value" class="form-label">Valor Financiado (R$)</label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="text" class="form-control money-input" id="financed_value" name="financed_value"
                            value="<?= $client['financed_value'] ? number_format($client['financed_value'], 2, ',', '.') : '' ?>">
                    </div>
                </div>
            </div>
                <div class="mb-3">
        <label for="attachments" class="form-label">Adicionar novos anexos</label>
        <input type="file" class="form-control" id="attachments" name="attachments[]" multiple
            accept="image/*,application/pdf,.doc,.docx,.xls,.xlsx">
        <div class="form-text">Formatos suportados: imagens, PDF, Word, Excel (Máx. 10MB por arquivo)</div>
    </div>
    <div class="d-flex justify-content-end gap-2 mt-4">
        <button type="reset" class="btn btn-outline-secondary">
            <i class="fas fa-redo me-1"></i> Limpar
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Atualizar Cliente
        </button>
    </div>
    </form>
        </div>
    </div>


    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="card-title mb-0"><i class="fas fa-paperclip me-2"></i> Anexos do Cliente</h5>
        </div>
        <div class="card-body">


            <?php include __DIR__ . '/_attachments.php'; ?>
        </div>
    </div>


</div>

<!-- Scripts para formatação de campos -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
    $(document).ready(function() {
        // Formatação de campos
        $('.cpf-input').mask('000.000.000-00', {
            reverse: true
        });
        $('.phone-input').mask('(00) 00000-0000');

        // Formatação de campos monetários
        $('.money-input').mask('#.##0,00', {
            reverse: true
        });

        // Converter campos monetários para formato numérico antes do envio
        $('#clientForm').on('submit', function() {
            $('.money-input').each(function() {
                let value = $(this).val().replace(/\./g, '').replace(',', '.');
                $(this).val(value);
            });
        });

        // Validação básica do formulário
        $('#clientForm').on('submit', function(e) {
            let valid = true;
            const cpf = $('#cpf').val().replace(/\D/g, '');

            // Validar CPF
            if (cpf.length !== 11) {
                alert('CPF deve ter 11 dígitos');
                valid = false;
            }

            if (!valid) {
                e.preventDefault();
            }
        });
    });
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>