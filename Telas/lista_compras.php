<?php
session_start();
require_once 'conexao.php';
require_once 'menudrop.php';

// Verifica perfil de acesso
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
    exit();
}

// Consulta todas as compras com LEFT JOIN
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
        a {
            text-decoration: none;
            color: blue;
        }
    </style>
</head>
<body>
    <h2>Lista de Compras</h2>
    <p><a href="nova_compra.php">Registrar nova compra</a></p>

    <table>
        <tr>
            <th>ID Compra</th>
            <th>Cliente</th>
            <th>Produto</th>
            <th>Funcionário</th>
            <th>Fornecedor</th>
            <th>Quantidade</th>
            <th>Valor</th>
        </tr>

        <?php if ($compras): ?>
            <?php foreach ($compras as $c): ?>
                <tr>
                    <td><?= $c['cod_compra'] ?></td>
                    <td><?= $c['nome_cliente'] ?? '—' ?></td>
                    <td><?= htmlspecialchars($c['produto_tipo']) ?></td>
                    <td><?= $c['nome_funcionario'] ?? '—' ?></td>
                    <td><?= $c['nome_fornecedor'] ?? '—' ?></td>
                    <td><?= $c['quantidade'] ?></td>
                    <td>R$ <?= number_format($c['vlr_compra'], 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">Nenhuma compra registrada.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>
