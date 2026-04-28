<?php
require_once 'config.php';      // ✅ ADICIONADO
require_once 'functions.php';
verificarLogin();

// Processar ADICIONAR
if ($_POST && isset($_POST['adicionar'])) {
    adicionarTransacao($_POST['nome'], $_POST['valor'], $_POST['tipo']);
}

// Processar EXCLUIR
if ($_POST && isset($_POST['excluir'])) {
    removerTransacao($_POST['transacao_id']);
    $sucesso = "✅ Transação excluída com sucesso!";
}

$saldo = calcularSaldo();
?>

<?php include 'header.php'; ?>

<!-- ALERTA DE SUCESSO (se houver exclusão) -->
<?php if (isset($sucesso)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $sucesso; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row mb-4">
    <!-- Saldo Total -->
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow h-100">
            <div class="card-body text-center">
                <i class="fas fa-wallet fa-2x text-success mb-3"></i>
                <h2 class="fw-bold"><?php echo formatarMoeda($saldo); ?></h2>
                <p class="text-muted">Saldo Atual</p>
            </div>
        </div>
    </div>
</div>

<!-- Formulário de Nova Transação -->
<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Nova Transação</h5>
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="row">
                <div class="col-md-5 mb-3">
                    <label class="form-label">Nome da Transação</label>
                    <input type="text" class="form-control" name="nome" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Valor (R$)</label>
                    <input type="number" step="0.01" class="form-control" name="valor" required>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Tipo</label>
                    <select class="form-select" name="tipo" required>
                        <option value="receita">Receita</option>
                        <option value="despesa">Despesa</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" name="adicionar" class="btn btn-success w-100">
                        <i class="fas fa-save"></i> Adicionar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Últimas Transações -->
<div class="card shadow">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Últimas 5 Transações</h5>
    </div>
    <div class="card-body">
        <?php if (empty($_SESSION['transacoes'])): ?>
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted">Nenhuma transação registrada</p>
                <a href="#" class="btn btn-primary mt-3">Adicionar primeira transação</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Data</th>
                            <th>Nome</th>
                            <th>Valor</th>
                            <th>Tipo</th>
                            <th>% Total</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $ultimas = array_slice(array_reverse($_SESSION['transacoes']), 0, 5);
                        foreach ($ultimas as $transacao): 
                        ?>
                        <tr>
                            <td><strong>#<?php echo $transacao['id']; ?></strong></td>
                            <td><?php echo $transacao['data']; ?></td>
                            <td><?php echo htmlspecialchars($transacao['nome']); ?></td>
                            <td>
                                <span class="badge <?php echo $transacao['tipo'] === 'receita' ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo formatarMoeda($transacao['valor']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?php echo $transacao['tipo'] === 'receita' ? 'bg-success' : 'bg-warning'; ?>">
                                    <?php echo ucfirst($transacao['tipo']); ?>
                                </span>
                            </td>
                            <td><strong><?php echo calcularPorcentagem($transacao['valor'], $transacao['tipo']); ?>%</strong></td>
                            <td>
                                <form method="POST" class="d-inline" onsubmit="return confirm('🗑️ Excluir #<?php echo $transacao['id']; ?>?')">
                                    <input type="hidden" name="transacao_id" value="<?php echo $transacao['id']; ?>">
                                    <button type="submit" name="excluir" class="btn btn-sm btn-outline-danger" title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="text-end mt-3">
                <a href="historico.php" class="btn btn-outline-primary">
                    <i class="fas fa-list me-2"></i>Ver Histórico Completo (<?php echo count($_SESSION['transacoes']); ?>)
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

</div> <!-- ✅ Fecha container do header.php -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>