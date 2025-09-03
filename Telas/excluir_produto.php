<?php 
session_start();
require_once 'conexao.php';
require_once 'menudrop.php';

// VERIFICA SE O USUÁRIO TEM PERMISSÃO DE ADM
if($_SESSION['perfil'] != 1){
    echo "<script>alert('Acesso Negado!');window.location.href='principal.php';</script>";
    exit();
}

// Busca todos os produtos já com o nome do fornecedor
$sql = "SELECT p.*, f.nome_fornecedor 
        FROM produto p
        INNER JOIN fornecedor f ON p.id_fornecedor = f.id_fornecedor
        ORDER BY p.tipo ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);


// SE um id for passado via get, exclui o produto
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    $id_produto = $_GET['id'];


    try {
        $stmt = $pdo->prepare("DELETE FROM produto WHERE id_produto = :id");
        $stmt->execute([':id' => $id_produto]);
        echo "<script>alert('Produto excluído com sucesso!');window.location.href='buscar_produto.php';</script>";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "<script>alert('Não é possível excluir este produto porque há compras vinculadas a ele.');window.location.href='buscar_produto.php';</script>";
        } else {
            echo "Erro: " . $e->getMessage();
        }
    }
    

}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Produto</title>
    <link rel="stylesheet" href="../CSS/busca.css">
</head>
<body>

<h2>Excluir Produto</h2>

<?php if (!empty($produtos)): ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Fornecedor</th>
            <th>Tipo</th>
            <th>Voltagem</th>
            <th>Descrição</th>
            <th>Marca</th>
            <th>Qtde</th>
            <th>Preço</th>
            <th>Validade</th>
            <th>Ações</th>
        </tr>

        <?php foreach($produtos as $produto): ?>
            <tr>
                <td><?= htmlspecialchars($produto['id_produto']) ?></td>
                <td><?= htmlspecialchars($produto['nome_fornecedor']) ?></td>
                <td><?= htmlspecialchars($produto['tipo']) ?></td>
                <td><?= htmlspecialchars($produto['voltagem']) ?></td>
                <td><?= htmlspecialchars($produto['descricao']) ?></td>
                <td><?= htmlspecialchars($produto['marca']) ?></td>
                <td><?= htmlspecialchars($produto['qtde']) ?></td>
                <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                <td><?= htmlspecialchars($produto['validade']) ?></td>
                <td>
                    <a href="excluir_produto.php?id=<?= htmlspecialchars($produto['id_produto']) ?> "class= "action-btn delete-btn 
                       "onclick="return confirm('Tem certeza que deseja excluir este produto?')">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Nenhum produto encontrado</p>
<?php endif; ?>

<a href="principal.php" class="back-btn">Voltar ao Menu Principal</a>

</body>
</html>
