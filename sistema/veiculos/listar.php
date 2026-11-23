<?php
require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../config/sessao.php";
exigir_login();

$titulo_pagina = "Veículos";
$msg = $_GET["msg"] ?? "";

$sql = "SELECT v.id, v.placa, v.marca, v.modelo, v.ano, c.nome AS cliente_nome, c.id AS cliente_id
        FROM veiculos v
        INNER JOIN clientes c ON c.id = v.cliente_id
        ORDER BY v.placa";
$veiculos = $conn->query($sql);

require __DIR__ . "/../includes/header.php";
?>
<h1 class="page-title">Veículos</h1>

<?php if ($msg === "ok"): ?>
    <div class="alert success">Veículo atualizado com sucesso.</div>
<?php elseif ($msg === "cad"): ?>
    <div class="alert success">Veículo cadastrado com sucesso.</div>
<?php endif; ?>

<div class="card">
    <div class="toolbar">
        <a class="btn" href="cadastrar.php">Novo veículo</a>
    </div>

    <?php if ($veiculos->num_rows === 0): ?>
        <p class="empty">Nenhum veículo cadastrado.</p>
    <?php else: ?>
        <table class="data">
            <thead>
            <tr>
                <th>ID</th>
                <th>Placa</th>
                <th>Marca / Modelo</th>
                <th>Ano</th>
                <th>Cliente</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($v = $veiculos->fetch_assoc()): ?>
                <tr>
                    <td><?= (int)$v["id"] ?></td>
                    <td><?= htmlspecialchars($v["placa"]) ?></td>
                    <td><?= htmlspecialchars($v["marca"] . " " . $v["modelo"]) ?></td>
                    <td><?= $v["ano"] !== null ? (int)$v["ano"] : "-" ?></td>
                    <td><?= htmlspecialchars($v["cliente_nome"]) ?></td>
                    <td>
                        <a class="btn btn-sm" href="editar.php?id=<?= (int)$v["id"] ?>">Editar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php require __DIR__ . "/../includes/footer.php"; ?>
