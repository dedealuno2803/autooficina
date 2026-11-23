<?php
require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../config/sessao.php";
exigir_login();

$titulo_pagina = "Novo veículo";
$erro = "";
$cliente_id = 0;
$placa = $marca = $modelo = "";
$ano = "";

$clientes = $conn->query("SELECT id, nome FROM clientes ORDER BY nome");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cliente_id = (int)($_POST["cliente_id"] ?? 0);
    $placa = strtoupper(trim($_POST["placa"] ?? ""));
    $marca = trim($_POST["marca"] ?? "");
    $modelo = trim($_POST["modelo"] ?? "");
    $ano = trim($_POST["ano"] ?? "");

    if ($cliente_id <= 0) {
        $erro = "Selecione o cliente proprietário do veículo.";
    } elseif ($placa === "" || $marca === "" || $modelo === "") {
        $erro = "Placa, marca e modelo são obrigatórios.";
    } else {
        $ano_val = $ano !== "" ? (int)$ano : null;
        $stmt = $conn->prepare("INSERT INTO veiculos (cliente_id, placa, marca, modelo, ano) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isssi", $cliente_id, $placa, $marca, $modelo, $ano_val);
        if ($stmt->execute()) {
            header("Location: listar.php?msg=cad");
            exit;
        }
        $erro = "Não foi possível cadastrar o veículo.";
    }
}

require __DIR__ . "/../includes/header.php";
?>
<h1 class="page-title">Novo veículo</h1>
<p style="color:#4a5568;margin-top:-0.5rem;">Ao cadastrar, o veículo deve ser associado a um cliente (RN07).</p>

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
                <label for="placa">Placa *</label>
                <input type="text" id="placa" name="placa" required maxlength="10" value="<?= htmlspecialchars($placa) ?>">
            </div>
            <div>
                <label for="marca">Marca *</label>
                <input type="text" id="marca" name="marca" required value="<?= htmlspecialchars($marca) ?>">
            </div>
            <div>
                <label for="modelo">Modelo *</label>
                <input type="text" id="modelo" name="modelo" required value="<?= htmlspecialchars($modelo) ?>">
            </div>
            <div>
                <label for="ano">Ano</label>
                <input type="number" id="ano" name="ano" min="1950" max="2030" value="<?= htmlspecialchars((string)$ano) ?>">
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn">Cadastrar</button>
            <a class="btn btn-secondary" href="listar.php">Cancelar</a>
        </div>
    </form>
</div>
<?php require __DIR__ . "/../includes/footer.php"; ?>
