<?php
require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../config/sessao.php";
exigir_login();

$titulo_pagina = "Novo agendamento";
$erro = "";
$cliente_id = $veiculo_id = 0;
$servico = $status = $observacoes = $data_agendamento = "";

$clientes = $conn->query("SELECT id, nome FROM clientes ORDER BY nome");
$veiculos = $conn->query(
    "SELECT v.id, v.placa, v.marca, v.modelo, v.cliente_id, c.nome AS cliente_nome
     FROM veiculos v INNER JOIN clientes c ON c.id = v.cliente_id
     ORDER BY v.placa"
);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cliente_id = (int)($_POST["cliente_id"] ?? 0);
    $veiculo_id = (int)($_POST["veiculo_id"] ?? 0);
    $data_agendamento = trim($_POST["data_agendamento"] ?? "");
    $servico = trim($_POST["servico"] ?? "");
    $status = trim($_POST["status"] ?? "Agendado");
    $observacoes = trim($_POST["observacoes"] ?? "");

    if ($cliente_id <= 0 || $veiculo_id <= 0 || $data_agendamento === "" || $servico === "") {
        $erro = "Cliente, veículo, data e serviço são obrigatórios.";
    } else {
        $data_sql = str_replace("T", " ", $data_agendamento);
        if (strlen($data_sql) === 16) {
            $data_sql .= ":00";
        }
        $stmt = $conn->prepare(
            "INSERT INTO agendamentos (cliente_id, veiculo_id, data_agendamento, servico, status, observacoes)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("iissss", $cliente_id, $veiculo_id, $data_sql, $servico, $status, $observacoes);
        if ($stmt->execute()) {
            header("Location: listar.php?msg=cad");
            exit;
        }
        $erro = "Não foi possível cadastrar o agendamento.";
    }
}

require __DIR__ . "/../includes/header.php";
?>
<h1 class="page-title">Novo agendamento</h1>

<?php if ($erro): ?>
    <div class="alert error"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<div class="card">
    <form method="post">
        <div class="form-grid">
            <div>
                <label for="cliente_id">Cliente *</label>
                <select id="cliente_id" name="cliente_id" required>
                    <option value="">— Selecione —</option>
                    <?php while ($c = $clientes->fetch_assoc()): ?>
                        <option value="<?= (int)$c["id"] ?>" <?= $cliente_id === (int)$c["id"] ? "selected" : "" ?>>
                            <?= htmlspecialchars($c["nome"]) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label for="veiculo_id">Veículo *</label>
                <select id="veiculo_id" name="veiculo_id" required>
                    <option value="">— Selecione —</option>
                    <?php while ($v = $veiculos->fetch_assoc()): ?>
                        <option value="<?= (int)$v["id"] ?>" <?= $veiculo_id === (int)$v["id"] ? "selected" : "" ?>>
                            <?= htmlspecialchars($v["placa"] . " — " . $v["marca"] . " " . $v["modelo"] . " (" . $v["cliente_nome"] . ")") ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label for="data_agendamento">Data/hora *</label>
                <input type="datetime-local" id="data_agendamento" name="data_agendamento" required
                       value="<?= htmlspecialchars($data_agendamento) ?>">
            </div>
            <div>
                <label for="servico">Serviço *</label>
                <input type="text" id="servico" name="servico" required value="<?= htmlspecialchars($servico) ?>">
            </div>
            <div>
                <label for="status">Status</label>
                <select id="status" name="status">
                    <?php foreach (["Agendado", "Em andamento", "Concluído"] as $st): ?>
                        <option value="<?= $st ?>" <?= $status === $st ? "selected" : "" ?>><?= $st ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="observacoes">Observações</label>
                <input type="text" id="observacoes" name="observacoes" value="<?= htmlspecialchars($observacoes) ?>">
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn">Cadastrar</button>
            <a class="btn btn-secondary" href="listar.php">Cancelar</a>
        </div>
    </form>
</div>
<?php require __DIR__ . "/../includes/footer.php"; ?>
