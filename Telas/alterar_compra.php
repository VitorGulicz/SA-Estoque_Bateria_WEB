<?php
session_start(); // Inicia a sessão
require_once 'conexao.php'; // Conexão com banco
require_once 'menudrop.php'; // Menu de navegação

// ======================== VERIFICA PERFIL ======================== //
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
    exit();
}

// ======================== BUSCA LISTA DE COMPRAS ======================== //
$compras = $pdo->query("
    SELECT c.cod_compra, cl.nome_cliente
    FROM compra c
    LEFT JOIN cliente cl ON c.cod_cliente = cl.id_cliente
    ORDER BY c.cod_compra DESC
")->fetchAll(PDO::FETCH_ASSOC);

// ======================== SELECIONA COMPRA SE HOUVER ID ======================== //
$compra = null;
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $cod_compra = intval($_GET['id']);
    $stmt = $pdo->prepare("
        SELECT c.*, p.tipo AS produto_tipo, p.qtde AS produto_estoque, p.preco AS produto_valor,
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
}

// ======================== LISTAS PARA COMBOBOX ======================== //
$produtos = $pdo->query("SELECT id_produto, tipo, qtde, preco, id_fornecedor FROM produto ORDER BY tipo")->fetchAll(PDO::FETCH_ASSOC);
$clientes = $pdo->query("SELECT id_cliente, nome_cliente FROM cliente ORDER BY nome_cliente")->fetchAll(PDO::FETCH_ASSOC);
$funcionarios = $pdo->query("SELECT id_funcionario, nome_funcionario FROM funcionario ORDER BY nome_funcionario")->fetchAll(PDO::FETCH_ASSOC);
$fornecedores = $pdo->query("SELECT id_fornecedor, nome_fornecedor FROM fornecedor ORDER BY nome_fornecedor")->fetchAll(PDO::FETCH_ASSOC);

// ======================== ATUALIZA COMPRA ======================== //
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($compra)) {
    $novo_produto = intval($_POST['produto']);
    $novo_cliente = intval($_POST['cliente']);
    $novo_funcionario = intval($_POST['funcionario']);
    $novo_fornecedor = intval($_POST['fornecedor']);
    $nova_quantidade = intval($_POST['quantidade']);
    $novo_valor = floatval($_POST['vlr_compra']);

    $stmt = $pdo->prepare("SELECT qtde FROM produto WHERE id_produto = :id");
    $stmt->execute(['id' => $novo_produto]);
    $produto_info = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto_info) {
        echo "<p>Produto não encontrado. <a href='javascript:history.back()'>Voltar</a></p>";
        exit();
    }

    if ($novo_produto == $compra['cod_produto']) {
        $novo_estoque = $produto_info['qtde'] - ($nova_quantidade - $compra['quantidade']);
    } else {
        $estoque_antigo = $compra['produto_estoque'] + $compra['quantidade'];
        $stmt = $pdo->prepare("UPDATE produto SET qtde = :qtde WHERE id_produto = :id");
        $stmt->execute(['qtde' => $estoque_antigo, 'id' => $compra['cod_produto']]);
        $novo_estoque = $produto_info['qtde'] - $nova_quantidade;
    }

    if ($novo_estoque < 0) {
        echo "<p>Estoque insuficiente! Estoque atual: {$produto_info['qtde']}. <a href='javascript:history.back()'>Voltar</a></p>";
        exit();
    }

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
        'id' => $compra['cod_compra']
    ]);

    $stmt = $pdo->prepare("UPDATE produto SET qtde = :qtde WHERE id_produto = :id");
    $stmt->execute(['qtde' => $novo_estoque, 'id' => $novo_produto]);

    echo "<script>alert('Compra atualizada com sucesso!');window.location.href='alterar_compra.php?id={$compra['cod_compra']}';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Alterar Compra</title>
<link rel="stylesheet" href="../CSS/busca.css">

<!-- JQuery + Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<link rel="stylesheet" href="../CSS/cadastro.css">

<style>
/* fundo escuro + texto branco para inputs e selects */
#searchCompra, .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #fff !important;
    background-color: #333 !important;
}
.select2-container--default .select2-selection--single {
    background-color: #333 !important;
    border: 1px solid #555 !important;
}
.select2-results__option {
    color: #fff !important;
    background-color: #333 !important;
}
.select2-results__option--highlighted {
    background-color: #555 !important;
    color: #fff !important;
}
.select2-search__field {
    color: #fff !important;
    background-color: #333 !important;
}
</style>
</head>
<body>
<h2>Alterar Compra</h2>

<div class="container">
    <!-- PESQUISAR COMPRA -->
    <div class="search-section">
        <label>Pesquisar Compra (ID ou Cliente):</label><br>
        <input type="hidden" id="searchCompra" placeholder="Digite ID ou nome do cliente...">

        <select id="selectCompra" size="5" class="select2" style="width:100%;">
            <option value="">-- Selecionar Compra --</option>
            <?php foreach ($compras as $c): ?>
                <option value="<?= $c['cod_compra'] ?>" <?= ($compra && $compra['cod_compra'] == $c['cod_compra']) ? 'selected' : '' ?>>
                    ID: <?= $c['cod_compra'] ?> - <?= htmlspecialchars($c['nome_cliente']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="button" onclick="buscarCompra()">Buscar Compra</button>
        <br><br>
    </div>

    <?php if($compra): ?>
    <!-- FORMULÁRIO DE EDIÇÃO -->
    <form method="post">
        <label>Produto:</label><br>
        <select name="produto" id="produto" class="select2" required style="width:100%;" onchange="atualizarProduto()">
            <?php foreach($produtos as $p): ?>
                <option value="<?= $p['id_produto'] ?>"
                    data-qtde="<?= $p['qtde'] ?>"
                    data-valor="<?= $p['preco'] ?>"
                    data-fornecedor="<?= $p['id_fornecedor'] ?>"
                    <?= $p['id_produto']==$compra['cod_produto'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['tipo']) ?> (Estoque: <?= $p['qtde'] ?>)
                </option>
            <?php endforeach; ?>
        </select><br>

        <label>Cliente:</label><br>
        <select name="cliente" class="select2" required style="width:100%;">
            <?php foreach($clientes as $c): ?>
                <option value="<?= $c['id_cliente'] ?>" <?= $c['id_cliente']==$compra['cod_cliente'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['nome_cliente']) ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label>Funcionário:</label><br>
        <select name="funcionario" class="select2" required style="width:100%;">
            <?php foreach($funcionarios as $f): ?>
                <option value="<?= $f['id_funcionario'] ?>" <?= $f['id_funcionario']==$compra['cod_funcionario'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($f['nome_funcionario']) ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label>Fornecedor:</label><br>
        <select name="fornecedor" id="cod_fornecedor" class="select2" required style="width:100%;">
            <?php foreach($fornecedores as $fr): ?>
                <option value="<?= $fr['id_fornecedor'] ?>" <?= $fr['id_fornecedor']==$compra['cod_fornecedor'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($fr['nome_fornecedor']) ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label>Quantidade:</label>
        <input type="number" name="quantidade" id="quantidade" min="1" placeholder="Max: <?= $compra['produto_estoque'] ?>" value="<?= $compra['quantidade'] ?>" required>
        <p>Estoque atual do produto: <span id="estoque_atual"><?= $compra['produto_estoque'] ?></span></p>

        <label>Valor da Compra:</label>
        <input type="number" step="0.01" name="vlr_compra" id="vlr_compra" value="<?= $compra['vlr_compra'] ?>" required>
        <br><br>

        <button type="submit">Atualizar Compra</button>
    </form>
    <?php endif; ?>
</div>

<p><a href="principal.php" class="back-btn">Voltar ao Menu Principal</a></p>

<script>
let precoUnitario = 0;

function buscarCompra() {
    const select = document.getElementById("selectCompra");
    const cod = select.value;
    if(cod) window.location = 'alterar_compra.php?id='+cod;
    else alert('Selecione uma compra primeiro!');
}

function atualizarProduto() {
    const select = document.getElementById("produto");
    const option = select.options[select.selectedIndex];
    const qtde = option.getAttribute("data-qtde");
    const valor = option.getAttribute("data-valor");
    const fornecedor = option.getAttribute("data-fornecedor");

    precoUnitario = parseFloat(valor) || 0;
    document.getElementById("quantidade").max = qtde;
    document.getElementById("quantidade").placeholder = "Max: " + qtde;
    document.getElementById("cod_fornecedor").value = fornecedor;
    document.getElementById("vlr_compra").value = precoUnitario.toFixed(2);
}

document.getElementById("quantidade").addEventListener("input", function() {
    const qtd = parseInt(this.value) || 0;
    document.getElementById("vlr_compra").value = (qtd * precoUnitario).toFixed(2);
});

document.getElementById("searchCompra").addEventListener("keyup", function() {
    const filter = this.value.toLowerCase();
    const select = document.getElementById("selectCompra");
    for (let i=0; i<select.options.length; i++) {
        const option = select.options[i];
        option.style.display = option.text.toLowerCase().includes(filter) ? '' : 'none';
    }
});

$(document).ready(function() {
    $('.select2').select2({ width: '100%' });
});

atualizarProduto();
</script>
</body>
</html>
