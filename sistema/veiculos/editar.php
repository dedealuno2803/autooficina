<?php
require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../config/sessao.php";
exigir_login();

$id = (int)($_GET["id"] ?? $_POST["id"] ?? 0);
if ($id <= 0) {
    header("Location: listar.php");
    exit;
}

$titulo_pagina = "Editar veículo";
$erro = "";
$clientes = $conn->query("SELECT id, nome FROM clientes ORDER BY nome");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cliente_id = (int)($_POST["cliente_id"] ?? 0);
    $placa = strtoupper(trim($_POST["placa"] ?? ""));
    $marca = trim($_POST["marca"] ?? "");
    $modelo = trim($_POST["modelo"] ?? "");
    $ano = trim($_POST["ano"] ?? "");

    if ($cliente_id <= 0 || $placa === "" || $marca === "" || $modelo === "") {
        $erro = "Cliente, placa, marca e modelo são obrigatórios.";
    } else {
        $ano_val = $ano !== "" ? (int)$ano : null;
        $stmt = $conn->prepare("UPDATE veiculos SET cliente_id=?, placa=?, marca=?, modelo=?, ano=? WHERE id=?");
        $stmt->bind_param("isssii", $cliente_id, $placa, $marca, $modelo, $ano_val, $id);
        if ($stmt->execute()) {
            header("Location: listar.php?msg=ok");
            exit;
        }
        $erro = "Não foi possível salvar as alterações.";
    }
}

$stmt = $conn->prepare("SELECT * FROM veiculos WHERE id=? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$veiculo = $stmt->get_result()->fetch_assoc();
if (!$veiculo) {
    header("Location: listar.php");
    exit;
}

require __DIR__ . "/../includes/header.php";
?>
<h1 class="page-title">Editar veículo #<?= (int)$veiculo["id"] ?></h1>

<?php if ($erro): ?>
    <div class="alert error"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<div class="card">
    <form method="post">
        <input type="hidden" name="id" value="<?= (int)$veiculo["id"] ?>">
        <div class="form-grid">
            <div>
                <label for="cliente_id">Cliente *</label>
                <select id="cliente_id" name="cliente_id" required>
                    <?php
                    $clientes->data_seek(0);
                    while ($c = $clientes->fetch_assoc()):
                        $sel = ((int)$veiculo["cliente_id"] === (int)$c["id"]) ? "selected" : "";
                        ?>
                        <option value="<?= (int)$c["id"] ?>" <?= $sel ?>><?= htmlspecialchars($c["nome"]) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label for="placa">Placa *</label>
                <input type="text" id="placa" name="placa" required maxlength="10"
                       value="<?= htmlspecialchars($veiculo["placa"]) ?>">
            </div>
            <div>
                <label for="marca">Marca *</label>
                <input type="text" id="marca" name="marca" required value="<?= htmlspecialchars($veiculo["marca"]) ?>">
            </div>
            <div>
                <label for="modelo">Modelo *</label>
                <input type="text" id="modelo" name="modelo" required value="<?= htmlspecialchars($veiculo["modelo"]) ?>">
            </div>
            <div>
                <label for="ano">Ano</label>
                <input type="number" id="ano" name="ano" min="1950" max="2030"
                       value="<?= $veiculo["ano"] !== null ? (int)$veiculo["ano"] : "" ?>">
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn">Salvar alterações</button>
            <a class="btn btn-secondary" href="listar.php">Cancelar</a>
        </div>
    </form>
</div>
<?php require __DIR__ . "/../includes/footer.php"; ?>
