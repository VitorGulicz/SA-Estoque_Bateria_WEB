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
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f4f8; padding: 20px; text-align: center; color: #333; }
    h2 { color: #2c3e50; margin-bottom: 20px; }
    form { margin-bottom: 30px; }
    input[type="text"] { padding: 10px; width: 250px; border: 1px solid #ccc; border-radius: 5px; outline: none; }
    input[type="text"]:focus { border-color: #2980b9; }
    button { padding: 10px 15px; border: none; background-color: #2980b9; color: white; border-radius: 5px; cursor: pointer; transition: background-color 0.2s ease; }
    button:hover { background-color: #1c5980; }
    table { margin: auto; border-collapse: separate; border-spacing: 0; width: 95%; max-width: 1000px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border-radius: 10px; overflow: hidden; }
    th, td { padding: 12px 15px; text-align: center; }
    th { background-color: #2980b9; color: white; font-weight: 600; }
    tr:nth-child(even) { background-color: #f7f9fb; }
    tr:hover { background-color: #d6eaf8; transition: 0.2s; }
    a { display: inline-block; margin: 5px 0; padding: 5px 10px; color: white; text-decoration: none; border-radius: 5px; background-color: #27ae60; transition: background-color 0.2s ease; }
    a:hover { background-color: #1e8449; }
    a.delete { background-color: #c0392b; }
    a.delete:hover { background-color: #922b21; }
    address { margin-top: 30px; font-size: 0.9em; color: #7f8c8d; }
    </style>
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
                    <a href="excluir_produto.php?id=<?= htmlspecialchars($produto['id_produto']) ?>" 
                       onclick="return confirm('Tem certeza que deseja excluir este produto?')">
                       Excluir
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Nenhum produto encontrado</p>
<?php endif; ?>

<a href="principal.php" class="back-link">Voltar</a>

</body>
</html>
