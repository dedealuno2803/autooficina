<?php
/**
 * Criptografia do CPF — LGPD (AES-256-CBC)
 * Chave alinhada ao script saep_db.sql (AutoOficina Pro).
 */
define("CRYPTO_KEY", hash("sha256", "AutoOficina_Pro_LGPD_Chave_2026", true));
define("CRYPTO_IV", "AutoOficinaIV16!");

function criptografar_cpf(string $cpf): string
{
    $cpf = preg_replace("/\D/", "", $cpf);
    if (strlen($cpf) === 11) {
        $cpf = substr($cpf, 0, 3) . "." . substr($cpf, 3, 3) . "." . substr($cpf, 6, 3) . "-" . substr($cpf, 9, 2);
    }
    $encrypted = openssl_encrypt($cpf, "AES-256-CBC", CRYPTO_KEY, 0, CRYPTO_IV);
    return $encrypted !== false ? $encrypted : "";
}

function descriptografar_cpf(string $cipher): string
{
    if ($cipher === "") {
        return "";
    }
    $plain = openssl_decrypt($cipher, "AES-256-CBC", CRYPTO_KEY, 0, CRYPTO_IV);
    return $plain !== false ? $plain : "";
}
