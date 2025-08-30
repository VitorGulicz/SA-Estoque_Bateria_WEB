<?php
session_start();
require_once 'conexao.php';
require_once 'menudrop.php';

// Verifica perfil de acesso
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
    exit();
}

// Verifica se o id da compra foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p>Compra não informada. <a href='lista_compras.php'>Voltar</a></p>";
    exit();
}

$id_compra = intval($_GET['id']);

// Busca a compra e os dados do produto
$stmt = $pdo->prepare("
    SELECT c.*, p.nome_produto, p.estoque 
    FROM compra c
    JOIN produto p ON c.cod_produto = p.id_produto
    WHERE c.cod_compra = :id
");
$stmt->execute(['id' => $id_compra]);
$compra = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$compra) {
    echo "<p>Compra não encontrada. <a href='lista_compras.php'>Voltar</a></p>";
    exit();
}

// Atualiza quantidade e estoque
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nova_quantidade = intval($_POST['quantidade']);
    $quantidade_antiga = intval($compra['quantidade']);
    $estoque_atual = intval($compra['estoque']);

    // Calcula novo estoque
    $novo_estoque = $estoque_atual - ($nova_quantidade - $quantidade_antiga);

    if ($novo_estoque < 0) {
        echo "<p>Estoque insuficiente! <a href='javascript:history.back()'>Voltar</a></p>";
        exit();
    }

    // Atualiza a compra
    $stmt = $pdo->prepare("UPDATE compra SET quantidade = :qtd WHERE cod_compra = :id");
    $stmt->execute(['qtd' => $nova_quantidade, 'id' => $id_compra]);

    // Atualiza o estoque do produto
    $stmt = $pdo->prepare("UPDATE produto SET estoque = :estoque WHERE id_produto = :id_produto");
    $stmt->execute(['estoque' => $novo_estoque, 'id_produto' => $compra['cod_produto']]);

    echo "<script>alert('Compra atualizada com sucesso!');window.location.href='lista_compras.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Compra</title>
</head>
<body>
    <h2>Editar Compra - Produto: <?=htmlspecialchars($compra['nome_produto'])?></h2>
    <form method="post">
        <label for="quantidade">Quantidade:</label>
        <input type="number" name="quantidade" id="quantidade" min="1" value="<?=htmlspecialchars($compra['quantidade'])?>" required>
        <p>Estoque atual: <?=htmlspecialchars($compra['estoque'])?></p>
        <button type="submit">Atualizar</button>
    </form>
    <p><a href="lista_compras.php">Voltar para lista de compras</a></p>
</body>
</html>
