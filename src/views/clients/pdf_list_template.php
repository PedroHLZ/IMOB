<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Clientes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
        }
        h2 {
            text-align: center;
            color: #003366;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ccc;
        }
        th {
            background-color: #003366;
            color: #ffffff;
            padding: 8px;
            text-align: center;
            font-weight: bold;
        }
        td {
            padding: 6px;
            border: 1px solid #ccc;
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h2>Lista de Clientes</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>CPF</th>
                <th>Telefone</th>
                <th>Corretor</th>
                <th>Status</th>
                <th>Observação</th>
                <th>Subsídio (R$)</th>
                <th>Financiamento (R$)</th>
                <th>Valor Total (R$)</th>
                
            </tr>
        </thead>
        <tbody>
            <?php
            $grouped = [];
            foreach ($clients as $client) {
                $broker = $client['broker'] ?: 'Sem Corretor';
                $grouped[$broker][] = $client;
            }
            ksort($grouped);
            ?>
            <?php foreach ($grouped as $broker => $brokerClients): ?>
                <tr style="background:#e6f0fa;font-weight:bold;color:#003366;"><td colspan="10">Corretor: <?= htmlspecialchars($broker) ?></td></tr>
                <?php foreach ($brokerClients as $client): ?>
                <tr>
                    <td><?= $client['id'] ?></td>
                    <td><?= htmlspecialchars($client['name']) ?></td>
                    <td><?= htmlspecialchars($client['cpf']) ?></td>
                    <td><?= htmlspecialchars($client['phone']) ?></td>
                    <td><?= htmlspecialchars($client['broker']) ?></td>
                    <td><?= htmlspecialchars($client['approval_status']) ?></td>
                    <td><?= htmlspecialchars($client['observation'] ?? '') ?></td>
                    <td><?= number_format((float)($client['subsidy_value'] ?? 0), 2, ',', '.') ?></td>
                    <td><?= number_format((float)($client['financed_value'] ?? 0), 2, ',', '.') ?></td>
                    <td><?= number_format((float)($client['subsidy_value'] ?? 0) + (float)($client['financed_value'] ?? 0), 2, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
