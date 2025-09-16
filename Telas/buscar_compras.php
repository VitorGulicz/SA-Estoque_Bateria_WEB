<?php
session_start(); // Inicia a sessão para verificar login e permissões
require_once 'conexao.php'; // Conexão com o banco de dados
require_once 'menudrop.php'; // Inclui o menu suspenso de navegação

// ======================== VERIFICA PERFIL ======================== //
// Somente usuários com perfil 1 podem acessar
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] != 1 && $_SESSION['perfil']!=5) {
    echo "<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
    exit();
}

// ======================== EXCLUIR COMPRA ======================== //
// Captura o ID da compra enviado via GET (links da tabela)
if (isset($_GET['id'])) {
    $cod_compra = intval($_GET['id']); // Converte para inteiro por segurança

    try {
        $pdo->beginTransaction(); // Inicia transação

        // Busca a compra para restaurar o estoque do produto
        $stmt = $pdo->prepare("SELECT cod_produto, quantidade FROM compra WHERE cod_compra = :id");
        $stmt->execute(['id' => $cod_compra]);
        $compra = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($compra) {
            // Atualiza estoque do produto
            $stmt = $pdo->prepare("UPDATE produto SET qtde = qtde + :quantidade WHERE id_produto = :id_produto");
            $stmt->execute([
                'quantidade' => $compra['quantidade'],
                'id_produto' => $compra['cod_produto']
            ]);

            // Exclui a compra do banco
            $stmt = $pdo->prepare("DELETE FROM compra WHERE cod_compra = :id");
            $stmt->execute(['id' => $cod_compra]);

            $pdo->commit(); // Confirma transação
            echo "<script>alert('Compra excluída com sucesso!'); window.location.href='".$_SERVER['PHP_SELF']."';</script>";
        } else {
            throw new Exception("Compra não encontrada!");
        }
    } catch (Exception $e) {
        $pdo->rollBack(); // Desfaz alterações em caso de erro
        echo "<script>alert('Erro ao excluir compra: ".$e->getMessage()."');</script>";
    }
}

// ======================== BUSCA TODAS AS COMPRAS ======================== //
try {
    $stmt = $pdo->prepare("
        SELECT 
            c.cod_compra,
            c.quantidade,
            c.vlr_compra,
            c.data_compra,
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
    $compras = $stmt->fetchAll(PDO::FETCH_ASSOC); // Array com todas as compras
} catch (PDOException $e) {
    echo "Erro ao buscar compras: " . $e->getMessage();
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["busca"])) {
    $busca = trim($_POST["busca"]);

    if (is_numeric($busca)) {
        $sql = "
            SELECT 
                c.cod_compra,
                c.quantidade,
                c.vlr_compra,
                c.data_compra,
                cl.nome_cliente,
                p.tipo AS produto_tipo,
                f.nome_funcionario,
                fr.nome_fornecedor
            FROM compra c
            LEFT JOIN cliente cl ON c.cod_cliente = cl.id_cliente
            LEFT JOIN produto p ON c.cod_produto = p.id_produto
            LEFT JOIN funcionario f ON c.cod_funcionario = f.id_funcionario
            LEFT JOIN fornecedor fr ON c.cod_fornecedor = fr.id_fornecedor
            WHERE c.cod_compra = :busca
            ORDER BY cl.nome_cliente ASC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca', $busca, PDO::PARAM_INT);
    } else {
        $sql = "
            SELECT 
                c.cod_compra,
                c.quantidade,
                c.vlr_compra,
                c.data_compra,
                cl.nome_cliente,
                p.tipo AS produto_tipo,
                f.nome_funcionario,
                fr.nome_fornecedor
            FROM compra c
            LEFT JOIN cliente cl ON c.cod_cliente = cl.id_cliente
            LEFT JOIN produto p ON c.cod_produto = p.id_produto
            LEFT JOIN funcionario f ON c.cod_funcionario = f.id_funcionario
            LEFT JOIN fornecedor fr ON c.cod_fornecedor = fr.id_fornecedor
            WHERE cl.nome_cliente LIKE :busca_nome
            ORDER BY cl.nome_cliente ASC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', $busca . '%', PDO::PARAM_STR);
    }

    $stmt->execute();
    $compras = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
<div class="container">
    <h2>Lista de Compras</h2>
    <div class="search-section">
    <form action="buscar_compras.php" method="POST">
        <label for="busca">Digite o id ou NOME(opcional):</label>
        <input type="text" id="busca" name="busca" placeholder="Digite o ID ou nome do cliente...">
        <button type="submit">Buscar</button>
    </form>
</div>

    <!-- Tabela que lista todas as compras -->
    
        <!-- Verifica se existem compras -->
        <?php if ($compras): ?>
            <div class="table-container">
    <table>
        <tr>
            <th>ID Compra</th>
            <th>Cliente</th>
            <th>Produto</th>
            <th>Funcionário</th>
            <th>Fornecedor</th>
            <th>Quantidade</th>
            <th>Valor</th>
            <th>Data da Compra</th>
            <th>Ações</th>
        </tr>

            <?php foreach ($compras as $c): ?>
                <tr>
                    <td><?= $c['cod_compra'] ?></td>
                    <td><?= htmlspecialchars($c['nome_cliente'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($c['produto_tipo'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($c['nome_funcionario'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($c['nome_fornecedor'] ?? '—') ?></td>
                    <td><?= $c['quantidade'] ?></td>
                    <td>R$ <?= number_format($c['vlr_compra'], 2, ',', '.') ?></td>
                    <td><?= date('d/m/Y', strtotime($c['data_compra'])) ?></td>
                    <td>
                        <!-- Link para editar compra -->
                        <a href="alterar_compra.php?id=<?= htmlspecialchars($c['cod_compra']) ?>" class="action-btn edit-btn"></a>

                        <!-- Link para excluir compra -->
                        <a href="?id=<?= htmlspecialchars($c['cod_compra']) ?>" class="action-btn delete-btn" onclick="return confirm('Tem certeza que deseja excluir esta compra?')"></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="9">Nenhuma compra registrada.</td>
            </tr>
        <?php endif; ?>
        <div>
    </table>
        </div>
    <!-- Botão para cadastrar uma nova compra -->
    <p><a href="principal.php" class="back-btn">Voltar ao Menu Principal</a></p>
</body>
</html>
                    