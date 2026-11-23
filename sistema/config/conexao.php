<?php
/**
 * Conexão com o banco — AutoOficina Pro
 * Ajuste usuário, senha e porta conforme o XAMPP local.
 */
$servidor = "localhost";
$usuario  = "root";
$senha    = "";
$banco    = "autooficina";
$porta    = 3306; // use 3307 se o MySQL do XAMPP estiver nessa porta

$conn = new mysqli($servidor, $usuario, $senha, $banco, $porta);

if ($conn->connect_error) {
    die("Erro ao conectar ao banco de dados: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
