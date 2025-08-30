<?php 
session_start();
require_once 'conexao.php';
require_once 'menudrop.php';

// Verifica perfil de acesso
if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado');window.location.href='principal.php';</script>";
    exit();
}

// Busca todos os fornecedores
$stmt = $pdo->query("SELECT id_fornecedor, nome_fornecedor FROM fornecedor ORDER BY nome_fornecedor ASC");
$fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fornecedor= trim($_POST['fornecedor']);
    $tipo= trim($_POST['tipo']);
    $voltagem = trim($_POST['voltagem']);
    $descricao = trim($_POST['descricao']);
    $marca = trim($_POST['marca']);
    $qtde = (int)$_POST['qtde'];
    $preco = str_replace(',', '.', str_replace('.', '', $_POST['preco']));
    $validade= trim($_POST['validade']);

    // Validações
    if (strlen($tipo) < 2) {
        echo "<script>alert('O nome do produto deve ter pelo menos 2 caracteres.');</script>";
    } elseif ($qtde < 0) {
        echo "<script>alert('A quantidade não pode ser negativa.');</script>";
    } elseif ($preco <= 0) {
        echo "<script>alert('O valor unitário deve ser maior que zero.');</script>";
    } else {
        try {
            $sql = "INSERT INTO produto (id_fornecedor, tipo, voltagem, descricao, marca, qtde, preco, validade) 
                    VALUES (:id_fornecedor, :tipo, :voltagem, :descricao, :marca, :qtde, :preco, :validade)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_fornecedor', $fornecedor);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':voltagem', $voltagem);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':marca', $marca);
            $stmt->bindParam(':qtde', $qtde, PDO::PARAM_INT);
            $stmt->bindParam(':preco', $preco);
            $stmt->bindParam(':validade', $validade);

            if ($stmt->execute()) {
                echo "<script>alert('Produto cadastrado com sucesso'); window.location.href='cadastro_produto.php';</script>";
            } else {
                $erro = $stmt->errorInfo();
                echo "<pre>Erro ao cadastrar produto: ";
                print_r($erro);
                echo "</pre>";
            }
        } catch (PDOException $e) {
            echo "<pre>Erro PDO: " . $e->getMessage() . "</pre>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cadastro de Produto</title>
<style>
    /* Seu CSS existente permanece igual */
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f4f8; color: #333; padding: 20px; text-align: center; }
    h2 { margin-bottom: 20px; color: #2c3e50; }
    form { max-width: 500px; margin: auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); text-align: left; }
    label { display: block; margin-top: 15px; font-weight: 500; }
    input[type="text"], input[type="number"], select { width: 100%; padding: 10px; margin-top: 5px; border-radius: 5px; border: 1px solid #ccc; outline: none; }
    input:focus, select:focus { border-color: #2980b9; }
    button { padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; margin-top: 15px; margin-right: 10px; color: white; transition: background-color 0.2s ease; }
    button[type="submit"] { background-color: #2980b9; }
    button[type="submit"]:hover { background-color: #1c5980; }
    button[type="reset"] { background-color: #c0392b; }
    button[type="reset"]:hover { background-color: #922b21; }
    a.back-link { display: inline-block; margin-top: 20px; padding: 8px 15px; background-color: #2980b9; color: white; border-radius: 5px; text-decoration: none; transition: background-color 0.2s ease; }
    a.back-link:hover { background-color: #1c5980; }
    address { margin-top: 30px; font-size: 0.9em; color: #7f8c8d; font-style: normal; }
</style>

<script src="../JS/mascara.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<body>

<h2>Cadastro de Produto</h2>

<form action="cadastro_produto.php" method="POST">
    <label for="tipo">Tipo do produto</label>
    <input type="text" id="tipo" name="tipo" required >

    <label for="fornecedor">Selecione um fornecedor:</label>
    <select id="fornecedor" name="fornecedor" style="width:100%;">
        <option value="">Escolha fornecedor</option>
        <?php foreach ($fornecedores as $f): ?>
            <option value="<?= $f['id_fornecedor'] ?>"><?= htmlspecialchars($f['nome_fornecedor']) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="voltagem">Voltagem do produto</label>
    <input type="text" id="voltagem" name="voltagem" required >

    <label for="descricao">Descrição do produto</label>
    <input type="text" id="descricao" name="descricao" required>

    <label for="marca">Marca do produto</label>
    <input type="text" id="marca" name="marca" required>

    <label for="qtde">Quantidade do produto</label>
    <input type="text" id="qtde" name="qtde" required>

    <label for="preco">Preço do produto </label>
    <input type="text" id="preco" name="preco" required>

    <label for="validade">Validade do produto</label>
    <input type="date" id="validade" name="validade" maxlength="10" placeholder="dd/mm/aaaa" required onkeypress="mascara(this,data1)">

  

    <button type="submit">Salvar</button>
    <button type="reset">Cancelar</button>
</form>

<a href="principal.php" class="back-link">Voltar</a>

<address>
    | Max Emanoel / estudante / desenvolvimento 
</address>

<script>
$(document).ready(function() {
    $('#fornecedor').select2({
        placeholder: "Escolha fornecedor",
        allowClear: true
    });
});
</script>

</body>
</html>
