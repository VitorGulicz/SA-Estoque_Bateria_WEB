<?php
session_start(); // Inicia a sessão para verificar login e permissões
require_once 'conexao.php'; // Conexão com o banco de dados
require_once 'menudrop.php'; // Inclui o menu suspenso de navegação

// Verifica perfil de acesso (somente perfil 1 pode acessar)
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
    exit(); // Finaliza execução se não tiver permissão
}

// Excluir compra
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir'])) {
    $cod_compra = intval($_POST['excluir']); // Pega o ID da compra enviada no POST
    try {
        $pdo->beginTransaction(); // Inicia uma transação no banco

        // Busca compra no banco para restaurar o estoque do produto
        $stmt = $pdo->prepare("SELECT cod_produto, quantidade FROM compra WHERE cod_compra = :id");
        $stmt->execute(['id' => $cod_compra]);
        $compra = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($compra) {
            // Atualiza o estoque devolvendo a quantidade do produto
            $stmt = $pdo->prepare("UPDATE produto SET qtde = qtde + :quantidade WHERE id_produto = :id_produto");
            $stmt->execute([
                'quantidade' => $compra['quantidade'],
                'id_produto' => $compra['cod_produto']
            ]);

            // Exclui a compra do banco de dados
            $stmt = $pdo->prepare("DELETE FROM compra WHERE cod_compra = :id");
            $stmt->execute(['id' => $cod_compra]);

            $pdo->commit(); // Confirma a transação
            echo "<script>alert('Compra excluída com sucesso!');</script>";
        } else {
            // Caso a compra não seja encontrada
            throw new Exception("Compra não encontrada!");
        }
    } catch (Exception $e) {
        // Em caso de erro, desfaz a transação
        $pdo->rollBack();
        echo "<script>alert('Erro ao excluir compra: " . $e->getMessage() . "');</script>";
    }
}

// Busca todas as compras no banco
try {
    $stmt = $pdo->prepare("
        SELECT 
            c.cod_compra,
            c.quantidade,
            c.vlr_compra,
            cl.nome_cliente,
            p.tipo AS produto_tipo,
            f.nome_funcionario,
            fr.nome_fornecedor
        FROM compra c
        LEFT JOIN cliente cl ON c.cod_cliente = cl.id_cliente
        LEFT JOIN produto p ON c.cod_produto = p.id_produto
        LEFT JOIN funcionario f ON c.cod_funcionario = f.id_funcionario
        LEFT JOIN fornecedor fr ON c.cod_fornecedor = fr.id_fornecedor
        ORDER BY c.cod_compra DESC
    ");
    $stmt->execute();
    $compras = $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna todas as compras como array
} catch (PDOException $e) {
    // Se ocorrer erro na consulta
    echo "Erro ao buscar compras: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Compras</title>
    <link rel="stylesheet" href="../CSS/busca.css"><!-- Importa o CSS -->
</head>
<body>
    <h2>Lista de Compras</h2>

    <!-- Tabela que lista todas as compras -->
    <table>
        <tr>
            <th>ID Compra</th>
            <th>Cliente</th>
            <th>Produto</th>
            <th>Funcionário</th>
            <th>Fornecedor</th>
            <th>Quantidade</th>
            <th>Valor</th>
            <th>Ações</th>
        </tr>

        <!-- Verifica se existem compras -->
        <?php if ($compras): ?>
            <?php foreach ($compras as $c): ?>
                <tr>
                    <td><?= $c['cod_compra'] ?></td>
                    <td><?= htmlspecialchars($c['nome_cliente'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($c['produto_tipo'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($c['nome_funcionario'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($c['nome_fornecedor'] ?? '—') ?></td>
                    <td><?= $c['quantidade'] ?></td>
                    <td>R$ <?= number_format($c['vlr_compra'], 2, ',', '.') ?></td>
                    <td>
                        <!-- Link para editar compra -->
                        <a href="alterar_compra.php?id=<?= $c['cod_compra'] ?>"  
                           class="action-btn edit-btn"
                           onsubmit="return confirm('Deseja realmente excluir esta compra?');">
                            <input type="hidden" name="excluir" value="<?= $c['cod_compra'] ?>">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Caso não existam compras -->
            <tr>
                <td colspan="8">Nenhuma compra registrada.</td>
            </tr>
        <?php endif; ?>
    </table>

    <!-- Botão para cadastrar uma nova compra -->
    <p><a href="principal.php" class="back-btn">🛒 Registrar nova compra</a></p>
</body>
</html>
