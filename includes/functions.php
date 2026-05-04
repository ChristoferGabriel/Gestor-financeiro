<?php
require_once 'config.php';


if (!isset($_SESSION['transacoes'])) {
    $_SESSION['transacoes'] = [];
}

function calcularSaldo() {
    $saldo = 0;
    foreach ($_SESSION['transacoes'] as $transacao) {
        if ($transacao['tipo'] === 'receita') {
            $saldo += $transacao['valor'];
        } else {
            $saldo -= $transacao['valor'];
        }
    }
    return $saldo;
}

function formatarMoeda($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

function calcularPorcentagem($valor, $tipo) {
    $totalReceitas = 0;
    foreach ($_SESSION['transacoes'] as $transacao) {
        if ($transacao['tipo'] === 'receita') {
            $totalReceitas += $transacao['valor'];
        }
    }
    
    if ($totalReceitas == 0) return 0;
    
        return round(($valor / $totalReceitas) * 100, 1);

function adicionarTransacao($nome, $valor, $tipo) {
    $transacao = [
        'id' => count($_SESSION['transacoes']) + 1,
        'nome' => $nome,
        'valor' => floatval($valor),
        'tipo' => $tipo,
        'data' => date('d/m/Y H:i')
    ];
    
    $_SESSION['transacoes'][] = $transacao;
}

function limparHistorico() {
    $_SESSION['transacoes'] = [];
}

function verificarLogin() {
    if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
        header('Location: login.php');
        exit();
    }
}
    
function removerTransacao($id) {
    foreach ($_SESSION['transacoes'] as $key => $transacao) {
        if ($transacao['id'] == $id) {
            unset($_SESSION['transacoes'][$key]);
            // Reindexar array
            $_SESSION['transacoes'] = array_values($_SESSION['transacoes']);
            // Atualizar IDs
            for ($i = 0; $i < count($_SESSION['transacoes']); $i++) {
                $_SESSION['transacoes'][$i]['id'] = $i + 1;
            }
            return true;
        }
    }
    return false;
}
?>  
