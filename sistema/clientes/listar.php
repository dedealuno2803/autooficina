<?php
require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../config/crypto.php";
require_once __DIR__ . "/../config/sessao.php";
exigir_login();

$titulo_pagina = "Clientes";
$termo = trim($_GET["q"] ?? "");
$msg = $_GET["msg"] ?? "";

if ($termo !== "") {
    $like = "%" . $termo . "%";
    $stmt = $conn->prepare(
        "SELECT id, nome, cpf, telefone, email FROM clientes
         WHERE nome LIKE ? OR telefone LIKE ? OR email LIKE ?
         ORDER BY nome"
    );
    $stmt->bind_param("sss", $like, $like, $like);
} else {
    $stmt = $conn->prepare("SELECT id, nome, cpf, telefone, email FROM clientes ORDER BY nome");
}
$stmt->execute();
$clientes = $stmt->get_result();

require __DIR__ . "/../includes/header.php";
?>
<h1 class="page-title">Clientes</h1>

<?php if ($msg === "ok"): ?>
    <div class="alert success">Cliente atualizado com sucesso.</div>
<?php elseif ($msg === "cad"): ?>
    <div class="alert success">Cliente cadastrado com sucesso.</div>
<?php endif; ?>

<div class="card">
    <div class="toolbar">
        <form method="get" action="">
            <input type="search" name="q" placeholder="Buscar por nome, telefone ou e-mail..."
                   value="<?= htmlspecialchars($termo) ?>">
            <button type="submit" class="btn">Buscar</button>
            <?php if ($termo !== ""): ?>
                <a class="btn btn-secondary" href="listar.php">Limpar</a>
            <?php endif; ?>
        </form>
        <a class="btn" href="cadastrar.php">Novo cliente</a>
    </div>

    <?php if ($clientes->num_rows === 0): ?>
        <p class="empty">Nenhum cliente encontrado<?= $termo !== "" ? " para o termo informado" : "" ?>.</p>
    <?php else: ?>
        <table class="data">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>CPF</th>
                <th>Telefone</th>
                <th>E-mail</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($c = $clientes->fetch_assoc()): ?>
                <tr>
                    <td><?= (int)$c["id"] ?></td>
                    <td><?= htmlspecialchars($c["nome"]) ?></td>
                    <td><?= htmlspecialchars(descriptografar_cpf($c["cpf"])) ?></td>
                    <td><?= htmlspecialchars($c["telefone"] ?? "") ?></td>
                    <td><?= htmlspecialchars($c["email"] ?? "") ?></td>
                    <td>
                        <a class="btn btn-sm" href="editar.php?id=<?= (int)$c["id"] ?>">Editar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php require __DIR__ . "/../includes/footer.php"; ?>
