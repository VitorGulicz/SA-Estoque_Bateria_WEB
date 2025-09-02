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

$cod_compra = intval($_GET['id']);

// Busca a compra com todos os dados
$stmt = $pdo->prepare("
    SELECT c.*, p.tipo AS produto_tipo, p.qtde AS produto_estoque,
           f.nome_funcionario, cl.nome_cliente, fr.nome_fornecedor
    FROM compra c
    LEFT JOIN produto p ON c.cod_produto = p.id_produto
    LEFT JOIN funcionario f ON c.cod_funcionario = f.id_funcionario
    LEFT JOIN cliente cl ON c.cod_cliente = cl.id_cliente
    LEFT JOIN fornecedor fr ON c.cod_fornecedor = fr.id_fornecedor
    WHERE c.cod_compra = :id
");
$stmt->execute(['id' => $cod_compra]);
$compra = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$compra) {
    echo "<p>Compra não encontrada. <a href='lista_compras.php'>Voltar</a></p>";
    exit();
}

// Busca todos os produtos, clientes, funcionários e fornecedores
$produtos = $pdo->query("SELECT id_produto, tipo, qtde FROM produto ORDER BY tipo")->fetchAll(PDO::FETCH_ASSOC);
$clientes = $pdo->query("SELECT id_cliente, nome_cliente FROM cliente ORDER BY nome_cliente")->fetchAll(PDO::FETCH_ASSOC);
$funcionarios = $pdo->query("SELECT id_funcionario, nome_funcionario FROM funcionario ORDER BY nome_funcionario")->fetchAll(PDO::FETCH_ASSOC);
$fornecedores = $pdo->query("SELECT id_fornecedor, nome_fornecedor FROM fornecedor ORDER BY nome_fornecedor")->fetchAll(PDO::FETCH_ASSOC);

// Atualiza compra
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novo_produto = intval($_POST['produto']);
    $novo_cliente = intval($_POST['cliente']);
    $novo_funcionario = intval($_POST['funcionario']);
    $novo_fornecedor = intval($_POST['fornecedor']);
    $nova_quantidade = intval($_POST['quantidade']);
    $novo_valor = floatval($_POST['vlr_compra']);

    // Busca estoque do novo produto
    $stmt = $pdo->prepare("SELECT qtde FROM produto WHERE id_produto = :id");
    $stmt->execute(['id' => $novo_produto]);
    $produto_info = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto_info) {
        echo "<p>Produto não encontrado. <a href='javascript:history.back()'>Voltar</a></p>";
        exit();
    }

    // Calcula estoque considerando alteração
    if ($novo_produto == $compra['cod_produto']) {
        $novo_estoque = $produto_info['qtde'] - ($nova_quantidade - $compra['quantidade']);
    } else {
        // Reverte estoque do produto antigo
        $estoque_antigo = $compra['produto_estoque'] + $compra['quantidade'];
        $stmt = $pdo->prepare("UPDATE produto SET qtde = :qtde WHERE id_produto = :id");
        $stmt->execute(['qtde' => $estoque_antigo, 'id' => $compra['cod_produto']]);

        // Estoque do novo produto
        $novo_estoque = $produto_info['qtde'] - $nova_quantidade;
    }

    if ($novo_estoque < 0) {
        echo "<p>Estoque insuficiente! Estoque atual: {$produto_info['qtde']}. <a href='javascript:history.back()'>Voltar</a></p>";
        exit();
    }

    // Atualiza a compra
    $stmt = $pdo->prepare("
        UPDATE compra SET
            cod_produto = :produto,
            cod_cliente = :cliente,
            cod_funcionario = :funcionario,
            cod_fornecedor = :fornecedor,
            quantidade = :qtd,
            vlr_compra = :vlr
        WHERE cod_compra = :id
    ");
    $stmt->execute([
        'produto' => $novo_produto,
        'cliente' => $novo_cliente,
        'funcionario' => $novo_funcionario,
        'fornecedor' => $novo_fornecedor,
        'qtd' => $nova_quantidade,
        'vlr' => $novo_valor,
        'id' => $cod_compra
    ]);

    // Atualiza estoque do novo produto
    $stmt = $pdo->prepare("UPDATE produto SET qtde = :qtde WHERE id_produto = :id");
    $stmt->execute(['qtde' => $novo_estoque, 'id' => $novo_produto]);

    echo "<script>alert('Compra atualizada com sucesso!');window.location.href='lista_compras.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Compra</title>
    <style>
        input[type="text"], select, input[type="number"] {
            width: 300px;
            padding: 5px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
<h2>Editar Compra</h2>
<form method="post">

    <label>Produto:</label><br>
    <input type="text" id="searchProduto" placeholder="Pesquisar produto...">
    <select name="produto" id="produto" size="5" required>
        <?php foreach ($produtos as $p): ?>
            <option value="<?= $p['id_produto'] ?>" <?= $p['id_produto'] == $compra['cod_produto'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($p['tipo']) ?> (Estoque: <?= $p['qtde'] ?>)
            </option>
        <?php endforeach; ?>
    </select>
    <br>

    <label>Cliente:</label><br>
    <select name="cliente" required>
        <?php foreach ($clientes as $c): ?>
            <option value="<?= $c['id_cliente'] ?>" <?= $c['id_cliente'] == $compra['cod_cliente'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['nome_cliente']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br>

    <label>Funcionário:</label><br>
    <select name="funcionario" required>
        <?php foreach ($funcionarios as $f): ?>
            <option value="<?= $f['id_funcionario'] ?>" <?= $f['id_funcionario'] == $compra['cod_funcionario'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($f['nome_funcionario']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br>

    <label>Fornecedor:</label><br>
    <select name="fornecedor" required>
        <?php foreach ($fornecedores as $fr): ?>
            <option value="<?= $fr['id_fornecedor'] ?>" <?= $fr['id_fornecedor'] == $compra['cod_fornecedor'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($fr['nome_fornecedor']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br>

    <label>Quantidade:</label>
    <input type="number" name="quantidade" min="1" value="<?= $compra['quantidade'] ?>" required>
    <p>Estoque atual do produto selecionado: <span id="estoque_atual"><?= $compra['produto_estoque'] ?></span></p>

    <label>Valor da Compra:</label>
    <input type="number" step="0.01" name="vlr_compra" value="<?= $compra['vlr_compra'] ?>" required>
    <br><br>

    <button type="submit">Atualizar Compra</button>
</form>

<p><a href="lista_compras.php">Voltar para lista de compras</a></p>

<script>
    // Filtrar produtos na combobox
    const searchInput = document.getElementById('searchProduto');
    const select = document.getElementById('produto');
    searchInput.addEventListener('keyup', function() {
        const filter = searchInput.value.toLowerCase();
        for (let i = 0; i < select.options.length; i++) {
            const option = select.options[i];
            option.style.display = option.text.toLowerCase().includes(filter) ? '' : 'none';
        }
    });

    // Atualiza estoque atual quando muda o produto
    select.addEventListener('change', function() {
        const selectedOption = select.options[select.selectedIndex];
        const estoqueText = selectedOption.text.match(/\(Estoque: (\d+)\)/);
        if (estoqueText) {
            document.getElementById('estoque_atual').textContent = estoqueText[1];
        }
    });
</script>
</body>
</html>
