<?php
session_start();

define('USUARIO_CORRETO', 'admin');
define('SENHA_HASH', password_hash('123456', PASSWORD_DEFAULT)); 
// ↑ GERA HASH NOVO toda vez que carregar (PERFEITO para testes)

if (!isset($_SESSION['transacoes'])) {
    $_SESSION['transacoes'] = [];
}
?>