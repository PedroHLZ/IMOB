<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Ficha Cadastral - <?= htmlspecialchars($client['name']) ?></title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #222;
            margin: 0;
            padding: 0;
            background: #f6f8fa;
        }

        .header-table {

            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .header-table td {
            border: none;
            padding: 2px 4px;
            vertical-align: middle;
        }

        .logo-cell {

            width: 110px;
            text-align: center;
        }

        .logo {
            width: 90px;
            height: auto;
            margin-bottom: 2px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            color: #000000ff;
            text-align: left;
            padding-left: 10px;
            letter-spacing: 1px;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #222;
            padding: 6px 8px;
            font-size: 11px;
        }

        .main-table th {
            background: #f3f3f3;
            font-weight: bold;
            text-align: left;
        }

        .section-title {
            color: #000000fd;
            font-weight: bold;
            text-align: center;
            font-size: 13px;
            border: 1px solid #000000ff;
            padding: 5px 0;
            margin-bottom: 0;
            letter-spacing: 1px;
        }

        .checkbox {
            display: inline-block;
            width: 13px;
            height: 13px;
            border: 1.2px solid #222;
            margin-right: 2px;
            vertical-align: middle;
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            line-height: 13px;
        }

        .assinatura {
            margin-top: 32px;
            text-align: center;
            font-size: 11px;
        }

        .assinatura .linha {
            border-bottom: 1.2px solid #222;
            width: 200px;
            margin: 0 auto 2px auto;
            height: 22px;
        }

        .small {
            font-size: 10px;
            color: #555;
        }

        .obs-area {
            height: 60px;
            border: 1px solid #bbb;
            background: #f9f9f9;
            margin-bottom: 8px;
        }

        .label {
            color: #0d6efd;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <?php
    // Função auxiliar para formatar valores monetários
    function formatCurrency($value)
    {
        if (!is_numeric($value)) {
            return 'R$ 0,00';
        }
        return 'R$ ' . number_format((float)$value, 2, ',', '.');
    }

    // Função auxiliar para gerar um checkbox com texto
    function generateCheckbox($isChecked, $label)
    {
        $checkMark = $isChecked ? 'X' : '&nbsp;';
        return "<span class=\"checkbox\">{$checkMark}</span> {$label}";
    }

    // Função auxiliar para formatar datas
    function formatDate($dateString)
    {
        if (empty($dateString) || $dateString === '0000-00-00') {
            return '';
        }
        return date('d/m/Y', strtotime($dateString));
    }
    ?>
    <table class="header-table">
        <tr>
            <td class="logo-cell" rowspan="2">
                <?php
                // O Dompdf precisa de um caminho absoluto para o arquivo de imagem ou uma imagem embutida.
                // Usar um Data URI é a forma mais confiável de embutir imagens.
                // Certifique-se de que o caminho para sua logo está correto. Este exemplo assume
                // que o arquivo LOGO.jpg está na raiz do seu projeto web (htdocs).
                $logoPath = $_SERVER['DOCUMENT_ROOT'] . '/LOGO.jpg';
                if (file_exists($logoPath)) {
                    $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
                    $logoData = base64_encode(file_get_contents($logoPath));
                    echo '<img src="data:image/' . $logoType . ';base64,' . $logoData . '" class="logo" alt="Logo">';
                }
                ?>
            </td>
            <td class="title" colspan="4">FICHA CADASTRAL</td>
            <td style="font-size:11px; text-align:right;">Ficha: ________</td>
            <td style="font-size:11px; text-align:right;">DATA: <?= date('d/m/Y') ?></td>
        </tr>
        <tr>
            <td colspan="5" style="height:2px;"></td>
        </tr>
    </table>

    <div class="section-title">DADOS DO CLIENTE</div>
    <table class="main-table">
        <tr>
            <th style="width:22%;">Cliente(s):</th>
            <td colspan="3"><?= htmlspecialchars($client['name']) ?></td>
            <th style="width:10%;">CPF:</th>
            <td><?= htmlspecialchars($client['cpf']) ?></td>
        </tr>
        <tr>
            <th>Fone:</th>
            <td><?= htmlspecialchars($client['phone']) ?></td>

            <th>Email:</th>
            <td colspan="3"><?= htmlspecialchars($client['email']) ?></td>
        </tr>
        <tr>
            <th>Data de Nascimento:</th>
            <td><?= formatDate($client['birthdate']) ?></td>
            <th>Estado civil</th>
            <td colspan="3">
                <?= generateCheckbox($client['civil_status'] == 'Solteiro(a)', 'Solteiro(a)') ?>
                <?= generateCheckbox($client['civil_status'] == 'Casado(a)', 'Casado(a)') ?>
                <?= generateCheckbox($client['civil_status'] == 'União Estável', 'União Estável') ?>
                <?= generateCheckbox($client['civil_status'] == 'Divorciado(a)', 'Divorciado(a)') ?>
            </td>
        </tr>
    </table>


<div class="section-title">DADOS DO DEPENDENTE</div>
<table class="main-table">
    <tr>
        <th style="width:22%;">Nome:</th>
        <td colspan="3"><?= htmlspecialchars($client['dependent_name']) ?></td>
        <th style="width:10%;">CPF:</th>
        <td style="width:20%;"><?= htmlspecialchars($client['dependent_cpf']) ?></td>
    </tr>
    <tr>
        <th>Data de Nascimento:</th>
        <td><?= formatDate($client['dependent_birthdate']) ?></td>
        <th>Parentesco:</th>
        <td colspan="3"><?= htmlspecialchars($client['dependent_relationship']) ?></td>
    </tr>
</table>


    <div class="section-title">RENDA</div>
    <table class="main-table">
        <tr>
            <th style="width:20%;">Formal:</th>
            <td style="width:30%;"><?= formatCurrency($client['formal_income']) ?></td>
            <th style="width:20%;">Informal:</th>
            <td><?= formatCurrency($client['informal_income']) ?></td>
        </tr>
        <tr>
            <th>Total R$:</th>
            <td colspan="3"><?php
                $formal = is_numeric($client['formal_income']) ? (float)$client['formal_income'] : 0;
                $informal = is_numeric($client['informal_income']) ? (float)$client['informal_income'] : 0;
                echo formatCurrency($formal + $informal);
            ?></td>
        </tr>
    </table>

    <div class="section-title">APROVAÇÃO</div>
    <table class="main-table">
        <tr>
            <th style="width:20%;">Aprovado</th>
            <th style="width:20%;">Reprovado</th>
            <th style="width:20%;">Condicionado</th>
            <th style="width:40%;">Corretor</th>
        </tr>
        <tr>
            <td style="text-align:center;">
                <span class="checkbox"><?= ($client['approval_status'] == 'Aprovado') ? 'X' : '&nbsp;' ?></span>
            </td>
            <td style="text-align:center;">
                <span class="checkbox"><?= ($client['approval_status'] == 'Reprovado') ? 'X' : '&nbsp;' ?></span>
            </td>
            <td style="text-align:center;">
                <span class="checkbox"><?= ($client['approval_status'] == 'Condicionado') ? 'X' : '&nbsp;' ?></span>
            </td>
            <td><?= htmlspecialchars($client['broker']) ?></td>
        </tr>
    </table>

    <div class="section-title">DADOS DO IMÓVEL</div>
    <table class="main-table">
        <tr>
            <th>Valor do Imóvel</th>
            <td><?= formatCurrency($client['property_value']) ?></td>
            <th>Valor Financiado</th>
            <td><?= formatCurrency($client['financed_value']) ?></td>
        </tr>
        <tr>
            <th>Valor Subsídio</th>
            <td><?= formatCurrency($client['subsidy_value']) ?></td>
            <th>Total Geral</th>
            <td><?= formatCurrency((float)($client['subsidy_value'] ?? 0) + (float)($client['financed_value'] ?? 0)) ?></td>
        </tr>
        <tr>
            <th>Prestação</th>
            <td colspan="3">Tempo: _______ meses &nbsp;&nbsp; Valor: ____________</td>
        </tr>
    </table>

<div class="section-title">OBSERVAÇÃO OFICIAL</div>
<div class="obs-area">
    <?= nl2br(htmlspecialchars($client['observation'] ?? '')) ?>
</div>



<div class="section-title">INFORMAÇÕES ADICIONAIS</div>
<table class="main-table">
    <tr>
        <th style="width:30%;">Programa MCMV</th>
        <td>
            <?= generateCheckbox($client['program_mcmv'] === 'Novo', 'Novo') ?>
            <?= generateCheckbox($client['program_mcmv'] === 'Usado', 'Usado') ?>
        </td>
        <th style="width:20%;">Matrícula</th>
        <td><?= htmlspecialchars($client['matricula'] ?? '') ?></td>
    </tr>
    <tr>
        <th>Agência</th>
        <td><?= htmlspecialchars($client['agencia'] ?? '') ?></td>
        <th>FGTS</th>
        <td>
            <?= generateCheckbox($client['fgts'] === 'Sim', 'Sim') ?>
            <?= generateCheckbox($client['fgts'] === 'Não', 'Não') ?>
        </td>
    </tr>
</table>





    <div class="small" style="margin-top:18px;">
        Pernambuco Imobiliária &copy; <?= date('Y') ?>
    </div>
</body>

</html>