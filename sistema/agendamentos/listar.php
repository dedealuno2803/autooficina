<?php
require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../config/sessao.php";
exigir_login();

$titulo_pagina = "Agendamentos";
$msg = $_GET["msg"] ?? "";

// Listagem ordenada pela data de agendamento + dados do cliente e do veículo
$sql = "SELECT a.id, a.data_agendamento, a.servico, a.status, a.observacoes,
               c.nome AS cliente_nome, c.telefone AS cliente_telefone,
               v.placa, v.marca, v.modelo
        FROM agendamentos a
        INNER JOIN clientes c ON c.id = a.cliente_id
        INNER JOIN veiculos v ON v.id = a.veiculo_id
        ORDER BY a.data_agendamento ASC";
$agendamentos = $conn->query($sql);

require __DIR__ . "/../includes/header.php";
?>
<h1 class="page-title">Agendamentos</h1>

<?php if ($msg === "ok"): ?>
    <div class="alert success">Data do agendamento atualizada com sucesso.</div>
<?php elseif ($msg === "cad"): ?>
    <div class="alert success">Agendamento cadastrado com sucesso.</div>
<?php endif; ?>

<div class="card">
    <div class="toolbar">
        <a class="btn" href="cadastrar.php">Novo agendamento</a>
    </div>

    <?php if ($agendamentos->num_rows === 0): ?>
        <p class="empty">Nenhum agendamento cadastrado.</p>
    <?php else: ?>
        <table class="data">
            <thead>
            <tr>
                <th>Data</th>
                <th>Cliente</th>
                <th>Veículo</th>
                <th>Serviço</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($a = $agendamentos->fetch_assoc()): ?>
                <tr>
                    <td><?= date("d/m/Y H:i", strtotime($a["data_agendamento"])) ?></td>
                    <td>
                        <?= htmlspecialchars($a["cliente_nome"]) ?><br>
                        <small style="color:#718096"><?= htmlspecialchars($a["cliente_telefone"] ?? "") ?></small>
                    </td>
                    <td>
                        <?= htmlspecialchars($a["placa"]) ?><br>
                        <small style="color:#718096"><?= htmlspecialchars($a["marca"] . " " . $a["modelo"]) ?></small>
                    </td>
                    <td><?= htmlspecialchars($a["servico"]) ?></td>
                    <td><?= htmlspecialchars($a["status"]) ?></td>
                    <td>
                        <a class="btn btn-sm" href="editar.php?id=<?= (int)$a["id"] ?>">Atualizar data</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php require __DIR__ . "/../includes/footer.php"; ?>
