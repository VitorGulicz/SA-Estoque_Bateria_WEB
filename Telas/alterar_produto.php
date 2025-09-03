<?php 
session_start();
require_once 'conexao.php';
require_once 'menudrop.php';

// Verifica permissão
if($_SESSION['perfil'] != 1){
    echo "<script>alert('Acesso Negado!');window.location.href='principal.php';</script>";
    exit();
}

// Inicializa variável
$produto = null;

// Busca produto via POST (formulário de busca)
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['busca_produto'])) {
    $busca = trim($_POST["busca_produto"]);

    if(is_numeric($busca)) {
        $sql = "SELECT * FROM produto WHERE id_produto = :busca";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM produto WHERE tipo LIKE :busca_tipo";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_tipo', "%$busca%", PDO::PARAM_STR);
    }
    $stmt->execute();
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$produto){
        echo "<script>alert('Produto não encontrado!');</script>";
    }
}

// Busca produto via GET (vindo da tela de listar/alterar)
if (!$produto && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM produto WHERE id_produto = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$produto){
        echo "<script>alert('Produto não encontrado!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Produto</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f4f8; color: #333; text-align: center; padding: 20px; }
        h2 { color: #2c3e50; margin-bottom: 20px; }
        form { margin: 20px auto; padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); max-width: 500px; text-align: left; }
        label { display: block; margin: 12px 0 5px; font-weight: 500; }
        input[type="text"], input[type="number"], select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; outline: none; }
        input:focus, select:focus { border-color: #2980b9; }
        button { padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; margin-top: 15px; margin-right: 10px; transition: background-color 0.2s ease; color: white; }
        button[type="submit"] { background-color: #2980b9; }
        button[type="submit"]:hover { background-color: #1c5980; }
        button[type="reset"] { background-color: #c0392b; }
        button[type="reset"]:hover { background-color: #922b21; }
        a.back-link { display: inline-block; margin-top: 20px; padding: 8px 15px; background-color: #2980b9; color: white; border-radius: 5px; text-decoration: none; transition: background-color 0.2s ease; }
        a.back-link:hover { background-color: #1c5980; }
        address { margin-top: 30px; font-size: 0.9em; color: #7f8c8d; font-style: normal; }
    </style>
</head>
<body>

<h2>Alterar Produto</h2>

<!-- Formulário de busca -->
<form action="alterar_produto.php" method="POST">
    <label for="busca_produto">Digite o ID ou NOME do produto:</label>
    <input type="text" id="busca_produto" name="busca_produto" required>
    <button type="submit">Buscar</button>
</form>

<?php if ($produto): ?>
    <!-- Formulário de alteração -->
    <form action="processa_alteracao_produto.php" method="POST">
        <input type="hidden" name="id_produto" value="<?=htmlspecialchars($produto['id_produto'])?>">

        <label for="fornecedor">Fornecedor:</label>
        <select id="fornecedor" name="fornecedor" required>
            <option value="">Selecione um fornecedor</option>
            <?php
            $stmt_fornecedor = $pdo->query("SELECT id_fornecedor, nome_fornecedor FROM fornecedor ORDER BY nome_fornecedor ASC");
            $fornecedores = $stmt_fornecedor->fetchAll(PDO::FETCH_ASSOC);
            foreach($fornecedores as $f) {
                $selected = ($f['id_fornecedor'] == $produto['id_fornecedor']) ? "selected" : "";
                echo "<option value='".htmlspecialchars($f['id_fornecedor'])."' $selected>".htmlspecialchars($f['nome_fornecedor'])."</option>";
            }
            ?>
        </select>

        <label for="tipo">Tipo do Produto:</label>
        <input type="text" id="tipo" name="tipo" value="<?=htmlspecialchars($produto['tipo'])?>" required>

        <label for="voltagem">Voltagem do Produto:</label>
        <input type="text" id="voltagem" name="voltagem" value="<?=htmlspecialchars($produto['voltagem'])?>" required>

        <label for="descricao">Descrição:</label>
        <input type="text" id="descricao" name="descricao" value="<?=htmlspecialchars($produto['descricao'])?>" required>

        <label for="marca">Marca:</label>
        <input type="text" id="marca" name="marca" value="<?=htmlspecialchars($produto['marca'])?>" required>

        <label for="qtde">Quantidade:</label>
        <input type="number" id="qtde" name="qtde" value="<?=htmlspecialchars($produto['qtde'])?>" required>

        <label for="preco">Preço do Produto:</label>
        <input type="text" step="0.01" id="preco" name="preco" value="<?=htmlspecialchars($produto['preco'])?>" required>

        <label for="validade">Validade:</label>
        <input type="text" id="validade" name="validade" value="<?=htmlspecialchars($produto['validade'])?>" maxlength="10" placeholder="dd/mm/aaaa" onkeypress="data1(this, event)">

        <button type="submit">Alterar</button>
        <button type="reset">Cancelar</button>
    </form>
<?php endif; ?>

<a href="principal.php" class="back-link">Voltar</a>

<address>
    | Max Emanoel / estudante / desenvolvimento 
</address>

</body>
</html>
