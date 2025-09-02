<?php
session_start();
require_once 'conexao.php';
require_once 'menudrop.php';

// Verifica perfil de acesso
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
    exit();
}

// Busca todas as compras
try {
    $compras = $pdo->query("
        SELECT c.cod_compra, c.quantidade, c.vlr_compra,
               p.tipo AS produto_tipo, p.qtde AS produto_estoque,
               cl.nome_cliente, f.nome_funcionario, fr.nome_fornecedor
        FROM compra c
        LEFT JOIN produto p ON c.cod_produto = p.id_produto
        LEFT JOIN cliente cl ON c.cod_cliente = cl.id_cliente
        LEFT JOIN funcionario f ON c.cod_funcionario = f.id_funcionario
        LEFT JOIN fornecedor fr ON c.cod_fornecedor = fr.id_fornecedor
        ORDER BY c.cod_compra DESC
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar compras: " . $e->getMessage();
    exit();
}

// Excluir compra
if (isset($_GET['excluir'])) {
    $cod_compra = intval($_GET['excluir']);
    try {
        $pdo->beginTransaction();

        // Busca compra para restaurar estoque
        $stmt = $pdo->prepare("SELECT cod_produto, quantidade FROM compra WHERE cod_compra = :id");
        $stmt->execute(['id' => $cod_compra]);
        $compra = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($compra) {
            // Atualiza estoque
            $stmt = $pdo->prepare("UPDATE produto SET qtde = qtde + :quantidade WHERE id_produto = :id_produto");
            $stmt->execute([
                'quantidade' => $compra['quantidade'],
                'id_produto' => $compra['cod_produto']
            ]);

            // Exclui compra
            $stmt = $pdo->prepare("DELETE FROM compra WHERE cod_compra = :id");
            $stmt->execute(['id' => $cod_compra]);

            $pdo->commit();
            echo "<script>alert('Compra excluída com sucesso!');window.location.href='lista_compras.php';</script>";
            exit();
        } else {
            throw new Exception("Compra não encontrada!");
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<script>alert('Erro ao excluir compra: " . $e->getMessage() . "');window.location.href='lista_compras.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Compras</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        a.button { padding: 5px 10px; background-color: red; color: white; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <h2>Lista de Compras</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Valor</th>
            <th>Funcionário</th>
            <th>Fornecedor</th>
            <th>Ações</th>
        </tr>
        <?php if ($compras): ?>
            <?php foreach ($compras as $c): ?>
                <tr>
                    <td><?= $c['cod_compra'] ?></td>
                    <td><?= htmlspecialchars($c['nome_cliente']) ?></td>
                    <td><?= htmlspecialchars($c['produto_tipo']) ?></td>
                    <td><?= $c['quantidade'] ?></td>
                    <td><?= number_format($c['vlr_compra'], 2, ',', '.') ?></td>
                    <td><?= htmlspecialchars($c['nome_funcionario']) ?></td>
                    <td><?= htmlspecialchars($c['nome_fornecedor']) ?></td>
                    <td>
                        <a href="lista_compras.php?excluir=<?= $c['cod_compra'] ?>" onclick="return confirm('Deseja realmente excluir esta compra?');" class="button">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="8">Nenhuma compra encontrada.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>

