<?php
session_start();
require_once 'conexao.php';
require_once 'menudrop.php';

// Verifica perfil de acesso
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
    exit();
}

// Busca dados para os selects
try {
    $clientes = $pdo->query("SELECT id_cliente, nome_cliente FROM cliente ORDER BY nome_cliente")->fetchAll(PDO::FETCH_ASSOC);
    $produtos = $pdo->query("SELECT id_produto, tipo, qtde FROM produto ORDER BY tipo")->fetchAll(PDO::FETCH_ASSOC);
    $funcionarios = $pdo->query("SELECT id_funcionario, nome_funcionario FROM funcionario ORDER BY nome_funcionario")->fetchAll(PDO::FETCH_ASSOC);
    $fornecedores = $pdo->query("SELECT id_fornecedor, nome_fornecedor FROM fornecedor ORDER BY nome_fornecedor")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar dados para combobox: " . $e->getMessage();
    exit();
}

// Captura dados do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cod_produto = !empty($_POST['cod_produto']) ? intval($_POST['cod_produto']) : null;
    $quantidade = !empty($_POST['quantidade']) ? intval($_POST['quantidade']) : null;
    $cod_funcionario = !empty($_POST['cod_funcionario']) ? intval($_POST['cod_funcionario']) : null;

    if (!$cod_produto || !$quantidade || !$cod_funcionario) {
        echo "<p>Produto, quantidade e funcionário são obrigatórios. <a href='nova_compra.php'>Voltar</a></p>";
        exit();
    }

    // Campos opcionais
    $cod_cliente = !empty($_POST['cod_cliente']) ? intval($_POST['cod_cliente']) : null;
    $cod_fornecedor = !empty($_POST['cod_fornecedor']) ? intval($_POST['cod_fornecedor']) : null;
    $vlr_compra = !empty($_POST['vlr_compra']) ? floatval($_POST['vlr_compra']) : 0;

    // Verifica estoque do produto
    $stmt = $pdo->prepare("SELECT qtde FROM produto WHERE id_produto = :id");
    $stmt->execute(['id' => $cod_produto]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto) {
        echo "<p>Produto não encontrado. <a href='nova_compra.php'>Voltar</a></p>";
        exit();
    }

    if ($quantidade > $produto['qtde']) {
        echo "<p>Estoque insuficiente! Estoque atual: {$produto['qtde']}. <a href='nova_compra.php'>Voltar</a></p>";
        exit();
    }

    // Insere compra
    $stmt = $pdo->prepare("
        INSERT INTO compra (cod_cliente, cod_produto, cod_funcionario, cod_fornecedor, quantidade, vlr_compra)
        VALUES (:cliente, :produto, :funcionario, :fornecedor, :quantidade, :vlr)
    ");
    $stmt->execute([
        ':cliente' => $cod_cliente,
        ':produto' => $cod_produto,
        ':funcionario' => $cod_funcionario,
        ':fornecedor' => $cod_fornecedor,
        ':quantidade' => $quantidade,
        ':vlr' => $vlr_compra
    ]);

    // Atualiza estoque
    $novo_estoque = $produto['qtde'] - $quantidade;
    $stmt = $pdo->prepare("UPDATE produto SET qtde = :qtde WHERE id_produto = :id");
    $stmt->execute(['qtde' => $novo_estoque, 'id' => $cod_produto]);

    echo "<script>alert('Compra registrada com sucesso!');window.location.href='lista_compras.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nova Compra</title>
    <style>
        input[type="text"], select, input[type="number"] {
            width: 300px;
            padding: 5px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <h2>Registrar Nova Compra</h2>
    <form method="post">

        <!-- Cliente -->
        <label>Cliente:</label><br>
        <input type="text" id="searchCliente" placeholder="Pesquisar cliente...">
        <select name="cod_cliente" id="cod_cliente" size="5">
            <option value="">-- Nenhum --</option>
            <?php foreach($clientes as $c): ?>
                <option value="<?= $c['id_cliente'] ?>"><?= htmlspecialchars($c['nome_cliente']) ?></option>
            <?php endforeach; ?>
        </select>
        <br>

        <!-- Produto -->
        <label>Produto:</label><br>
        <input type="text" id="searchProduto" placeholder="Pesquisar produto...">
        <select name="cod_produto" id="cod_produto" size="5" required>
            <?php foreach($produtos as $p): ?>
                <option value="<?= $p['id_produto'] ?>">
                    <?= htmlspecialchars($p['tipo']) ?> (Estoque: <?= $p['qtde'] ?>)
                </option>
            <?php endforeach; ?>
        </select>
        <br>

        <!-- Funcionário -->
        <label>Funcionário:</label><br>
        <input type="text" id="searchFuncionario" placeholder="Pesquisar funcionário...">
        <select name="cod_funcionario" id="cod_funcionario" size="5" required>
            <option value="">-- Nenhum --</option>
            <?php foreach($funcionarios as $f): ?>
                <option value="<?= $f['id_funcionario'] ?>"><?= htmlspecialchars($f['nome_funcionario']) ?></option>
            <?php endforeach; ?>
        </select>
        <br>

        <!-- Fornecedor -->
        <label>Fornecedor:</label><br>
        <input type="text" id="searchFornecedor" placeholder="Pesquisar fornecedor...">
        <select name="cod_fornecedor" id="cod_fornecedor" size="5">
            <option value="">-- Nenhum --</option>
            <?php foreach($fornecedores as $f): ?>
                <option value="<?= $f['id_fornecedor'] ?>"><?= htmlspecialchars($f['nome_fornecedor']) ?></option>
            <?php endforeach; ?>
        </select>
        <br>

        <!-- Quantidade e Valor -->
        <label>Quantidade:</label>
        <input type="number" name="quantidade" min="1" required>
        <br><br>
        <label>Valor da Compra:</label>
        <input type="number" step="0.01" name="vlr_compra" required>
        <br><br>

        <button type="submit">Registrar Compra</button>
    </form>

    <p><a href="lista_compras.php">Voltar para lista de compras</a></p>

    <!-- Busca nas combobox -->
    <script>
        function enableSearch(inputId, selectId) {
            const searchInput = document.getElementById(inputId);
            const select = document.getElementById(selectId);
            searchInput.addEventListener('keyup', function() {
                const filter = searchInput.value.toLowerCase();
                for (let i = 0; i < select.options.length; i++) {
                    const option = select.options[i];
                    option.style.display = option.text.toLowerCase().includes(filter) ? '' : 'none';
                }
            });
        }

        enableSearch('searchCliente', 'cod_cliente');
        enableSearch('searchProduto', 'cod_produto');
        enableSearch('searchFuncionario', 'cod_funcionario');
        enableSearch('searchFornecedor', 'cod_fornecedor');
    </script>
</body>
</html>
