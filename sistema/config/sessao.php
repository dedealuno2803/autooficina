<?php
/**
 * Controle de sessão com tempo de expiração (RNF01)
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Tempo máximo de inatividade em segundos (30 minutos)
define("SESSAO_TIMEOUT", 1800);

function base_url(string $path = ""): string
{
    // Caminho relativo a partir da pasta do sistema
    $script = str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"] ?? ""));
    // Se estiver em subpasta (clientes, veiculos, agendamentos), sobe um nível
    if (preg_match("#/(clientes|veiculos|agendamentos)$#", $script)) {
        $script = dirname($script);
    }
    $base = rtrim($script, "/");
    if ($base === "" || $base === ".") {
        $base = "";
    }
    $path = ltrim($path, "/");
    return $base . ($path !== "" ? "/" . $path : "");
}

function exigir_login(): void
{
    if (empty($_SESSION["usuario_id"])) {
        header("Location: " . base_url("login.php"));
        exit;
    }

    $agora = time();
    if (isset($_SESSION["ultimo_acesso"]) && ($agora - (int)$_SESSION["ultimo_acesso"]) > SESSAO_TIMEOUT) {
        session_unset();
        session_destroy();
        header("Location: " . base_url("login.php") . "?expirado=1");
        exit;
    }

    $_SESSION["ultimo_acesso"] = $agora;
}

function usuario_logado(): ?array
{
    if (empty($_SESSION["usuario_id"])) {
        return null;
    }
    return [
        "id"     => $_SESSION["usuario_id"],
        "nome"   => $_SESSION["usuario_nome"] ?? "",
        "email"  => $_SESSION["usuario_email"] ?? "",
        "perfil" => $_SESSION["usuario_perfil"] ?? "",
    ];
}
