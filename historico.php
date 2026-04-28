<?php
require_once 'includes/functions.php';
verificarLogin();

if ($_POST && isset($_POST['limpar'])) {
    limparHistorico();
    header('Location: historico.php');
    exit();
}

$saldo = calcularSaldo();
?>

<?php include 'includes/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-history me-2 text-primary"></i>Histórico Completo</h2>
    <div>
        <span class="badge bg-primary fs-6 me-2"><?php echo count($_SESSION['transacoes']); ?> transações</span>
        <span class="h4 fw-bold"><?php echo formatarMoeda($saldo); ?></span>
    </div>
</div>

<?php if ($_POST && isset($_POST['limpar'])): ?>
    <div class="alert alert-success">Histórico limpo com sucesso!</div>
<?php endif; ?>

<div class="card-body p-0">
    <?php if (empty($_SESSION['transacoes'])): ?>
        <div class="text-center py-5">
            <i class="fas fa-history fa-4x text-muted mb-4"></i>
            <h4>Nenhum registro encontrado</h4>
            <p class="text-muted">Adicione transações no dashboard para ver o histórico aqui.</p>
            <a href="index.php" class="btn btn-primary">Voltar ao Dashboard</a>
        </div>
    <?php else: ?>
        <?php 
        // Processar exclusão se houver
        if ($_POST && isset($_POST['excluir'])) {
            if (removerTransacao($_POST['transacao_id'])) {
                $sucesso = "Transação excluída com sucesso!";
            } else {
                $erro = "Erro ao excluir transação!";
            }
        }
        ?>
        
        <?php if (isset($sucesso)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $sucesso; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($erro)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $erro; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Data/Hora</th>
                        <th>Nome</th>
                        <th>Valor</th>
                        <th>Tipo</th>
                        <th>Porcentagem</th>
                        <th>Impacto no Saldo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['transacoes'] as $transacao): ?>
                    <tr>
                        <td><strong>#<?php echo $transacao['id']; ?></strong></td>
                        <td><?php echo $transacao['data']; ?></td>
                        <td><?php echo htmlspecialchars($transacao['nome']); ?></td>
                        <td>
                            <strong><?php echo formatarMoeda($transacao['valor']); ?></strong>
                        </td>
                        <td>
                            <span class="badge <?php echo $transacao['tipo'] === 'receita' ? 'bg-success' : 'bg-danger'; ?>">
                                <?php echo ucfirst($transacao['tipo']); ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-info">
                                <?php echo calcularPorcentagem($transacao['valor'], $transacao['tipo']); ?>%
                            </span>
                        </td>
                        <td>
                            <?php 
                            $impacto = $transacao['tipo'] === 'receita' ? '+' . formatarMoeda($transacao['valor']) : '-' . formatarMoeda($transacao['valor']);
                            $classe = $transacao['tipo'] === 'receita' ? 'text-success' : 'text-danger';
                            echo "<span class='$classe fw-bold'>$impacto</span>";
                            ?>
                        </td>
                        <td>
                            <form method="POST" class="d-inline" onsubmit="return confirm('Deseja realmente excluir a transação #<?php echo $transacao['id']; ?>?')">
                                <input type="hidden" name="transacao_id" value="<?php echo $transacao['id']; ?>">
                                <button type="submit" name="excluir" class="btn btn-sm btn-danger" title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-primary">
                    <tr>
                        <th colspan="4" class="text-end">Saldo Final:</th>
                        <th colspan="4" class="text-center h4 fw-bold">
                            <?php echo formatarMoeda($saldo); ?>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php endif; ?>
</div>