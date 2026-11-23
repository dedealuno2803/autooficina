<?php
require_once __DIR__ . "/config/conexao.php";
require_once __DIR__ . "/config/sessao.php";
exigir_login();

$u = usuario_logado();
$titulo_pagina = "Início";

// Contagens rápidas
$q_clientes = (int)$conn->query("SELECT COUNT(*) c FROM clientes")->fetch_assoc()["c"];
$q_veiculos = (int)$conn->query("SELECT COUNT(*) c FROM veiculos")->fetch_assoc()["c"];
$q_agend = (int)$conn->query("SELECT COUNT(*) c FROM agendamentos")->fetch_assoc()["c"];

require __DIR__ . "/includes/header.php";
?>
<h1 class="page-title">Olá, <?= htmlspecialchars($u["nome"]) ?>!</h1>
<p style="color:#4a5568;margin-top:-0.5rem;margin-bottom:1.25rem;">
    Bem-vindo ao painel principal. Utilize o menu para gerenciar clientes, veículos e agendamentos.
</p>

<div class="dashboard-grid">
    <div class="dash-card">
        <a href="clientes/listar.php">Clientes</a>
        <p><?= $q_clientes ?> cadastrado(s)<br>Busca, edição e proteção de CPF</p>
    </div>
    <div class="dash-card">
        <a href="veiculos/listar.php">Veículos</a>
        <p><?= $q_veiculos ?> cadastrado(s)<br>Associados a um cliente</p>
    </div>
    <div class="dash-card">
        <a href="agendamentos/listar.php">Agendamentos</a>
        <p><?= $q_agend ?> registro(s)<br>Ordenados por data</p>
    </div>
</div>

<div class="card" style="margin-top:1.5rem;">
    <h2 style="margin-top:0;font-size:1.1rem;color:#1a365d;">Acesso rápido</h2>
    <div class="toolbar">
        <a class="btn" href="clientes/listar.php">Gerenciar clientes</a>
        <a class="btn" href="veiculos/listar.php">Gerenciar veículos</a>
        <a class="btn" href="agendamentos/listar.php">Gerenciar agendamentos</a>
        <a class="btn btn-secondary" href="logout.php">Sair do sistema</a>
    </div>
</div>
<?php require __DIR__ . "/includes/footer.php"; ?>
