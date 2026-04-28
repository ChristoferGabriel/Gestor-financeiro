<?php
require_once 'config.php';

/**
 * Calcula o saldo total
 */
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

/**
 * Formata valor monetário
 */
function formatarMoeda($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

/**
 * Calcula porcentagem da transação em relação ao total de receitas
 */
function calcularPorcentagem($valor, $tipo) {
    $totalReceitas = 0;
    foreach ($_SESSION['transacoes'] as $transacao) {
        if ($transacao['tipo'] === 'receita') {
            $totalReceitas += $transacao['valor'];
        }
    }
    
    if ($totalReceitas == 0) return 0;
    
    if ($tipo === 'receita') {
        return round(($valor / $totalReceitas) * 100, 1);
    } else {
        return round(($valor / $totalReceitas) * 100, 1);
    }
}

/**
 * Adiciona nova transação
 */
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

/**
 * Limpa histórico
 */
function limparHistorico() {
    $_SESSION['transacoes'] = [];
}

/**
 * Verifica se usuário está logado
 */
function verificarLogin() {
    if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
        header('Location: login.php');
        exit();
    }
}

/**
 * Remove uma transação pelo ID
 */
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