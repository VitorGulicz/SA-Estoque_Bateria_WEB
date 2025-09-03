<?php
session_start();
require_once 'conexao.php';
require_once 'menudrop.php';

// Verifica perfil de acesso
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
    exit();
}

// Excluir compra
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir'])) {
    $cod_compra = intval($_POST['excluir']);
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
            echo "<script>alert('Compra excluída com sucesso!');</script>";
        } else {
            throw new Exception("Compra não encontrada!");
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<script>alert('Erro ao excluir compra: " . $e->getMessage() . "');</script>";
    }
}

// Busca todas as compras
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
    $compras = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar compras: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Compras</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #666;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        a.button, button.button {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            color: white;
        }
        a.button {
            background-color: blue;
        }
        button.button {
            background-color: red;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
    </style>
</head>
<body>
    <h2>Lista de Compras</h2>
    <p><a href="nova_compra.php" class="button" style="background-color:green;">Registrar nova compra</a></p>

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
                        <div class="action-buttons">
                            <a href="editar_compra.php?id=<?= $c['cod_compra'] ?>" class="button">Editar</a>
                            <form method="post" style="margin:0;" onsubmit="return confirm('Deseja realmente excluir esta compra?');">
                                <input type="hidden" name="excluir" value="<?= $c['cod_compra'] ?>">
                                <button type="submit" class="button">Excluir</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">Nenhuma compra registrada.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>
