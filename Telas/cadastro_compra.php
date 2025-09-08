<?php
session_start();// Inicia a sessão
require_once 'conexao.php'; // Conexão com o banco de dados
require_once 'menudrop.php';// Importa o menu de navegação

// Verifica perfil de acesso (apenas perfil 1 tem acesso)
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
    exit();
}

// Se o formulário for enviado (via POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cod_cliente = $_POST['cod_cliente'] ?? null;
    $cod_produto = $_POST['cod_produto'] ?? null;
    $cod_funcionario = $_POST['cod_funcionario'] ?? null;
    $quantidade = $_POST['quantidade'] ?? null;
    $vlr_compra = $_POST['vlr_compra'] ?? null;
    $cod_fornecedor = $_POST['cod_fornecedor'] ?? null;

    if ($cod_produto && $cod_funcionario && $quantidade && $vlr_compra && $cod_fornecedor) {
        try {
            $pdo->beginTransaction();

            // Inserir compra
            $stmt = $pdo->prepare("
                INSERT INTO compra (cod_cliente, cod_produto, cod_funcionario, quantidade, vlr_compra, cod_fornecedor) 
                VALUES (:cod_cliente, :cod_produto, :cod_funcionario, :quantidade, :vlr_compra, :cod_fornecedor)
            ");
            $stmt->execute([
                ':cod_cliente' => $cod_cliente ?: null,
                ':cod_produto' => $cod_produto,
                ':cod_funcionario' => $cod_funcionario,
                ':quantidade' => $quantidade,
                ':vlr_compra' => $vlr_compra,
                ':cod_fornecedor' => $cod_fornecedor
            ]);

            // Atualizar estoque do produto
            $stmt2 = $pdo->prepare("UPDATE produto SET qtde = qtde - :qtd WHERE id_produto = :id");
            $stmt2->execute([
                ':qtd' => $quantidade,
                ':id' => $cod_produto
            ]);

            $pdo->commit(); // Confirma a transação
            echo "<script>alert('Compra registrada com sucesso!');window.location.href='cadastro_compra.php';</script>";
            exit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo "Erro ao registrar compra: " . $e->getMessage();
        }
    } else {
        echo "<script>alert('Preencha todos os campos obrigatórios!');</script>";
    }
}

// Busca os dados do banco para preencher os selects
try {
    $clientes = $pdo->query("SELECT id_cliente, nome_cliente FROM cliente ORDER BY nome_cliente")->fetchAll(PDO::FETCH_ASSOC);
    $produtos = $pdo->query("
        SELECT p.id_produto, p.tipo, p.qtde, p.preco, f.id_fornecedor, f.nome_fornecedor
        FROM produto p
        LEFT JOIN fornecedor f ON f.id_fornecedor = p.id_fornecedor
        ORDER BY p.tipo
    ")->fetchAll(PDO::FETCH_ASSOC);
    $funcionarios = $pdo->query("SELECT id_funcionario, nome_funcionario FROM funcionario ORDER BY nome_funcionario")->fetchAll(PDO::FETCH_ASSOC);
    $fornecedores = $pdo->query("SELECT id_fornecedor, nome_fornecedor FROM fornecedor ORDER BY nome_fornecedor")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar dados para combobox: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nova Compra</title>
    <link rel="stylesheet" href="../CSS/cadastro.css">

    <style>
        .black-select {
            background-color: #000;
            color: #fff;
            border: 1px solid #444;
            padding: 5px;
            border-radius: 4px;
        }
        .black-select option {
            background-color: #000;
            color: #fff;
        }
        .search-input {
            background-color: #000;
            color: #fff;
            border: 1px solid #444;
            padding: 5px;
            border-radius: 4px;
            width: 100%;
            margin-bottom: 5px;
        }
    </style>

    <!-- JQuery + Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
<h2>Registrar Nova Compra</h2>
<form method="post">

    <!-- Cliente -->
    <label>Cliente:</label><br>
    <input type="text" id="searchCliente" class="search-input" placeholder="Pesquisar cliente...">
    <select name="cod_cliente" id="cod_cliente" size="5" class="black-select select2">
        <option value="">-- Nenhum --</option>
        <?php foreach($clientes as $c): ?>
            <option value="<?= $c['id_cliente'] ?>"><?= htmlspecialchars($c['nome_cliente']) ?></option>
        <?php endforeach; ?>
    </select>
    <br>

    <!-- Produto -->
    <label>Produto:</label><br>
    <input type="text" id="searchProduto" class="search-input" placeholder="Pesquisar produto...">
    <select name="cod_produto" id="cod_produto" size="5" required onchange="atualizarProduto()" class="black-select select2">
        <?php foreach($produtos as $p): ?>
            <option 
                value="<?= $p['id_produto'] ?>"
                data-qtde="<?= $p['qtde'] ?>"
                data-valor="<?= $p['preco'] ?>"
                data-fornecedor="<?= $p['id_fornecedor'] ?>"
            >
                <?= htmlspecialchars($p['tipo']) ?> (Estoque: <?= $p['qtde'] ?>)
            </option>
        <?php endforeach; ?>
    </select>
    <br>

    <!-- Quantidade -->
    <label>Quantidade:</label>
    <input type="number" id="quantidade" name="quantidade" min="1" required placeholder="Informe a quantidade">
    <br><br>

    <!-- Valor -->
    <label>Valor da Compra:</label>
    <input type="number" step="0.01" id="vlr_compra" name="vlr_compra" required>
    <br><br>

    <!-- Fornecedor -->
    <label>Fornecedor:</label><br>
    <input type="text" id="searchFornecedor" class="search-input" placeholder="Pesquisar fornecedor...">
    <select name="cod_fornecedor" id="cod_fornecedor" size="5" required class="black-select select2">
        <option value="">-- Selecione --</option>
        <?php foreach($fornecedores as $f): ?>
            <option value="<?= $f['id_fornecedor'] ?>"><?= htmlspecialchars($f['nome_fornecedor']) ?></option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <!-- Funcionário -->
    <label>Funcionário:</label><br>
    <input type="text" id="searchFuncionario" class="search-input" placeholder="Pesquisar funcionário...">
    <select name="cod_funcionario" id="cod_funcionario" size="5" required class="black-select select2">
        <?php foreach($funcionarios as $f): ?>
            <option value="<?= $f['id_funcionario'] ?>"><?= htmlspecialchars($f['nome_funcionario']) ?></option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <button type="submit">Registrar Compra</button>
</form>

<p><a href="principal.php" class="back-btn">Voltar ao Menu Principal</a></p>

<script>
let precoUnitario = 0;

function atualizarProduto() {
    const select = document.getElementById("cod_produto");
    const option = select.options[select.selectedIndex];
    const qtde = option.getAttribute("data-qtde");
    const valor = option.getAttribute("data-valor");
    const fornecedor = option.getAttribute("data-fornecedor");

    precoUnitario = parseFloat(valor) || 0;

    const inputQtd = document.getElementById("quantidade");
    inputQtd.max = qtde;
    inputQtd.placeholder = "Máx: " + qtde;

    document.getElementById("cod_fornecedor").value = fornecedor;
    document.getElementById("vlr_compra").value = precoUnitario.toFixed(2);
}

document.getElementById("quantidade").addEventListener("input", function () {
    const qtd = parseInt(this.value) || 0;
    document.getElementById("vlr_compra").value = (qtd * precoUnitario).toFixed(2);
});

// Função para pesquisa em qualquer select
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

// Ativa pesquisa para todos
enableSearch('searchCliente', 'cod_cliente');
enableSearch('searchProduto', 'cod_produto');
enableSearch('searchFornecedor', 'cod_fornecedor');
enableSearch('searchFuncionario', 'cod_funcionario');

atualizarProduto();

// Ativa o Select2
$(document).ready(function() {
    $('.select2').select2({
        width: '100%'
    });
});
</script>
</body>
</html>
