<?php
session_start();
require_once 'conexao.php';
require_once 'menudrop.php';

// Verifica perfil de acesso
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
    exit();
}

// Captura dados do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cod_cliente = intval($_POST['cod_cliente']);
    $cod_produto = intval($_POST['cod_produto']);
    $cod_funcionario = intval($_POST['cod_funcionario']);
    $cod_fornecedor = intval($_POST['cod_fornecedor']);
    $quantidade = intval($_POST['quantidade']);
    $vlr_compra = floatval($_POST['vlr_compra']);

    // Verifica estoque do produto
    $stmt = $pdo->prepare("SELECT estoque FROM produto WHERE id_produto = :id");
    $stmt->execute(['id' => $cod_produto]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto) {
        echo "<p>Produto não encontrado. <a href='nova_compra.php'>Voltar</a></p>";
        exit();
    }

    if ($quantidade > $produto['estoque']) {
        echo "<p>Estoque insuficiente! Estoque atual: {$produto['estoque']}. <a href='nova_compra.php'>Voltar</a></p>";
        exit();
    }

    // Insere compra
    $stmt = $pdo->prepare("
        INSERT INTO compra (cod_cliente, cod_produto, cod_funcionario, cod_fornecedor, quantidade, vlr_compra)
        VALUES (:cliente, :produto, :funcionario, :fornecedor, :quantidade, :vlr)
    ");
    $stmt->execute([
        'cliente' => $cod_cliente,
        'produto' => $cod_produto,
        'funcionario' => $cod_funcionario,
        'fornecedor' => $cod_fornecedor,
        'quantidade' => $quantidade,
        'vlr' => $vlr_compra
    ]);

    // Atualiza estoque
    $novo_estoque = $produto['estoque'] - $quantidade;
    $stmt = $pdo->prepare("UPDATE produto SET estoque = :estoque WHERE id_produto = :id");
    $stmt->execute(['estoque' => $novo_estoque, 'id' => $cod_produto]);

    echo "<script>alert('Compra registrada com sucesso!');window.location.href='lista_compras.php';</script>";
    exit();
}

// Busca dados para os selects
$clientes = $pdo->query("SELECT id_cliente, nome_cliente FROM cliente ORDER BY nome_cliente")->fetchAll(PDO::FETCH_ASSOC);
$produtos = $pdo->query("SELECT id_produto, tipo FROM produto ORDER BY tipo")->fetchAll(PDO::FETCH_ASSOC);
$funcionarios = $pdo->query("SELECT id_funcionario, nome_funcionario FROM funcionario ORDER BY nome_funcionario")->fetchAll(PDO::FETCH_ASSOC);
$fornecedores = $pdo->query("SELECT id_fornecedor, nome_fornecedor FROM fornecedor ORDER BY nome_fornecedor")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nova Compra</title>
</head>
<body>
    <h2>Registrar Nova Compra</h2>
    <form method="post">
        <label>Cliente:</label>
        <select name="cod_cliente" required>
            <option value="">Selecione</option>
            <?php foreach($clientes as $c): ?>
                <option value="<?= $c['id_cliente'] ?>"><?= htmlspecialchars($c['nome_cliente']) ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label>Produto:</label>
        <select name="cod_produto" required>
            <option value="">Selecione</option>
            <?php foreach($produtos as $p): ?>
                <option value="<?= $p['id_produto'] ?>">
                    <?= htmlspecialchars($p['tipo']) ?> (Estoque: <?= $p['tipo'] ?>)
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label>Funcionário:</label>
        <select name="cod_funcionario" required>
            <option value="">Selecione</option>
            <?php foreach($funcionarios as $f): ?>
                <option value="<?= $f['id_funcionario'] ?>"><?= htmlspecialchars($f['nome_funcionario']) ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label>Fornecedor:</label>
        <select name="cod_fornecedor" required>
            <option value="">Selecione</option>
            <?php foreach($fornecedores as $f): ?>
                <option value="<?= $f['id_fornecedor'] ?>"><?= htmlspecialchars($f['nome_fornecedor']) ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label>Quantidade:</label>
        <input type="number" name="quantidade" min="1" required>
        <br><br>

        <label>Valor da Compra:</label>
        <input type="number" step="0.01" name="vlr_compra" required>
        <br><br>

        <button type="submit">Registrar Compra</button>
    </form>

    <p><a href="lista_compras.php">Voltar para lista de compras</a></p>
</body>
</html>
