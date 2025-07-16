<?php ob_start(); ?>

<h1 class="mb-4">Cadastrar Novo Cliente</h1>

<form method="POST" action="/clients/store" enctype="multipart/form-data" id="wizardForm">
    <div id="step-1" class="wizard-step">
        <h5>Etapa 1: Dados Pessoais</h5>
        <div class="mb-3">
            <label for="name" class="form-label">Nome Completo</label>
            <input type="text" class="form-control" id="name" name="name" >
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="cpf" class="form-label">CPF</label>
                <input type="text" class="form-control" id="cpf" name="cpf" >
            </div>
            <div class="col-md-4 mb-3">
                <label for="birthdate" class="form-label">Data de Nascimento</label>
                <input type="date" class="form-control" id="birthdate" name="birthdate" >
            </div>
            <div class="col-md-4 mb-3">
                <label for="phone" class="form-label">Telefone</label>
                <input type="text" class="form-control" id="phone" name="phone" >
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="col-md-6 mb-3">
                <label for="civil_status" class="form-label">Estado Civil</label>
                <select class="form-select" id="civil_status" name="civil_status">
                    <option value="">-- Selecione --</option>
                    <option value="Solteiro(a)">Solteiro(a)</option>
                    <option value="Casado(a)">Casado(a)</option>
                    <option value="União Estável">União Estável</option>
                    <option value="Divorciado(a)">Divorciado(a)</option>
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label for="broker" class="form-label">Corretor Responsável</label>
            <select class="form-select" id="broker" name="broker">
                <option value="">-- Selecione um corretor --</option>
                <?php foreach ($brokers as $broker): ?>
                    <option value="<?= htmlspecialchars($broker['name']) ?>"><?= htmlspecialchars($broker['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
          <div class="d-flex justify-content-end gap-2 mt-4">        <button type="button" class="btn btn-primary" onclick="nextStep(2)">Próxima Etapa</button></div>

    </div>

    <div id="step-2" class="wizard-step" style="display:none;">
        <h5>Etapa 2: Renda e Aprovação</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="formal_income" class="form-label">Renda Formal (R$)</label>
                <input type="number" step="0.01" class="form-control" id="formal_income" name="formal_income">
            </div>
            <div class="col-md-6 mb-3">
                <label for="informal_income" class="form-label">Renda Informal (R$)</label>
                <input type="number" step="0.01" class="form-control" id="informal_income" name="informal_income">
            </div>
        </div>
        <div class="mb-3">
            <label for="approval_status" class="form-label">Status de Aprovação</label>
            <select class="form-select" id="approval_status" name="approval_status">
                <option value="">-- Selecione --</option>
                <option value="Aprovado">Aprovado</option>
                <option value="Reprovado">Reprovado</option>
                <option value="Condicionado">Condicionado</option>
            </select>
        </div>
           <div class="d-flex justify-content-end gap-2 mt-4"> 
        <button type="button" class="btn btn-secondary" onclick="prevStep(1)">Voltar</button>
        <button type="button" class="btn btn-primary" onclick="nextStep(3)">Próxima Etapa</button>
        </div>
    </div>

    <div id="step-3" class="wizard-step" style="display:none;">
        <h5>Etapa 3: Imóvel e Dependente</h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="property_value" class="form-label">Valor do Imóvel (R$)</label>
                <input type="number" step="0.01" class="form-control" id="property_value" name="property_value">
            </div>
            <div class="col-md-4 mb-3">
                <label for="financed_value" class="form-label">Valor Financiado (R$)</label>
                <input type="number" step="0.01" class="form-control" id="financed_value" name="financed_value">
            </div>
            <div class="col-md-4 mb-3">
                <label for="subsidy_value" class="form-label">Valor do Subsídio (R$)</label>
                <input type="number" step="0.01" class="form-control" id="subsidy_value" name="subsidy_value">
            </div>
        </div>
        <div class="mb-3">
            <label for="total_value" class="form-label">Total Geral (R$)</label>
            <input type="number" step="0.01" class="form-control" id="total_value" name="total_value">
        </div>
        <h6 class="mt-4">Dados do Dependente</h6>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label for="dependent_name" class="form-label">Nome do Dependente</label>
                <input type="text" class="form-control" id="dependent_name" name="dependent_name">
            </div>
            <div class="col-md-3 mb-3">
                <label for="dependent_cpf" class="form-label">CPF do Dependente</label>
                <input type="text" class="form-control" id="dependent_cpf" name="dependent_cpf">
            </div>
            <div class="col-md-3 mb-3">
                <label for="dependent_birthdate" class="form-label">Data de Nascimento do Dependente</label>
                <input type="date" class="form-control" id="dependent_birthdate" name="dependent_birthdate">
            </div>
            <div class="col-md-3 mb-3">
                <label for="dependent_relationship" class="form-label">Parentesco</label>
                <input type="text" class="form-control" id="dependent_relationship" name="dependent_relationship">
            </div>
        </div>
           <div class="d-flex justify-content-end gap-2 mt-4"> 
        <button type="button" class="btn btn-secondary" onclick="prevStep(2)">Voltar</button>
        <button type="button" class="btn btn-primary" onclick="nextStep(4)">Próxima Etapa</button></div>
    </div>

    <div id="step-4" class="wizard-step" style="display:none;">
        <h5>Etapa 4: Observações, Informações Adicionais e Anexos</h5>
        <div class="mb-3">
            <label for="observation" class="form-label">Observação Oficial</label>
            <textarea class="form-control" id="observation" name="observation" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Programa MCMV</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="program_mcmv" id="mcmv_novo" value="Novo">
                <label class="form-check-label" for="mcmv_novo">Novo</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="program_mcmv" id="mcmv_usado" value="Usado">
                <label class="form-check-label" for="mcmv_usado">Usado</label>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">FGTS</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="fgts" id="fgts_sim" value="Sim">
                <label class="form-check-label" for="fgts_sim">Sim</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="fgts" id="fgts_nao" value="Não">
                <label class="form-check-label" for="fgts_nao">Não</label>
            </div>
        </div>
        <div class="mb-3">
            <label for="attachments" class="form-label">Anexos (fotos, PDFs)</label>
            <input type="file" class="form-control" id="attachments" name="attachments[]" multiple accept="image/*,application/pdf">
        </div>
           <div class="d-flex justify-content-end gap-2 mt-4"> 
        <button type="button" class="btn btn-secondary" onclick="prevStep(3)">Voltar</button>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="/clients" class="btn btn-secondary">Cancelar</a>
        </div>
    </div>
    </div>
</form>

<script>
function nextStep(step) {
    document.querySelectorAll('.wizard-step').forEach(e => e.style.display = 'none');
    document.getElementById('step-' + step).style.display = '';
    window.scrollTo(0,0);
}
function prevStep(step) {
    document.querySelectorAll('.wizard-step').forEach(e => e.style.display = 'none');
    document.getElementById('step-' + step).style.display = '';
    window.scrollTo(0,0);
}
</script>

<?php $content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
