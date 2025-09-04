<?php
session_start();
require_once 'conexao.php';
require_once 'menudrop.php';

//VERIFICA SE O USUÁRIO TEM PERMISSÃO DE ADM

if($_SESSION['perfil']!= 1){
    echo "<script>alert('Acesso Negado!');window.location.href='principal.php';</script>";
    exit();
}

//INICIALIZA AS VARIAVEIS
$fornecedor = null;

//SE O FORMULARIO FOR ENVIADO, BUSCA O USUARIO PELO ID OU PELO NOME
if ($_SERVER["REQUEST_METHOD"]=="POST") {
    if (!empty($_POST['busca_fornecedor'])) {
        $busca = trim($_POST["busca_fornecedor"]);

    if(is_numeric($busca)) {
        $sql = "SELECT * FROM fornecedor WHERE id_fornecedor = :busca";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca' ,$busca,PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM fornecedor WHERE nome_fornecedor LIKE :busca_nome";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', "$busca%", PDO::PARAM_STR);
    }
    $stmt->execute();
    $fornecedor = $stmt->fetch(PDO::FETCH_ASSOC);

    // SE O USUÁRIO NÃO FOR ENCONTRADO, EXIBE UM ALERTA

    if(!$fornecedor){
        echo "<script>alert('Fornecedor não encontrado!');</script>";
    }
}
    } elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (!empty($_GET['id'])) {
            $busca = trim($_GET["id"]);

        if(is_numeric($busca)) {
            $sql = "SELECT * FROM fornecedor WHERE id_fornecedor = :busca";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':busca' ,$busca,PDO::PARAM_INT);
        } else {
            $sql = "SELECT * FROM fornecedor WHERE nome_fornecedor LIKE :busca_nome";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':busca_nome', "$busca%", PDO::PARAM_STR);
        }
        $stmt->execute();
        $fornecedor = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(!$fornecedor){
            echo "<script>alert('Fornecedor não encontrado!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Fornecedor</title>
<!--Certifique-se de que o Java script está sendo carregado corretamente-->
<script src="../JS/scripts.js"></script>
<script src="../JS/mascara.js"></script>
<link rel="stylesheet" href="../CSS/cadastro.css">
</head>
<body>
<h2>Alterar Fornecedor</h2>
<!--FORMULÁRIO PARA BUSCAR FORNECEDOR-->
    <form action="alterar_fornecedor.php" method="POST">
        <label for="busca_fornecedor">Digite o id ou NOME do fornecedor:</label>
        <input type="text" id="busca_fornecedor" name="busca_fornecedor" required onkeyup="buscarSugestoes()" >
        <div id="sugestoes">
        </div>
        <button type="submit">Buscar</button>
    </form>

    <?php if ($fornecedor): ?>
        <form action="processa_alteracao_fornecedor.php" method="POST">
            <input type="hidden" name="id_fornecedor" value="<?=htmlspecialchars($fornecedor['id_fornecedor'])?>">

            <label for="nome_fornecedor">Nome do Fornecedor:</label>
            <input type="text" id="nome_fornecedor" name="nome_fornecedor" value="<?=htmlspecialchars($fornecedor['nome_fornecedor'])?>" required onkeypress="mascara(this,nome1)">

            <label for="cnpj">CNPJ do Fornecedor: </label>
            <input type="text" id="cnpj" name="cnpj"  value="<?=htmlspecialchars($fornecedor['cnpj'])?>" required onkeypress="mascara(this,cnpj1)" maxlength="18">

            <label for="endereco">Endereço:</label>
            <input type="text" id="endereco" name="endereco" value="<?=htmlspecialchars($fornecedor['endereco'])?>" required >
            
            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" value="<?=htmlspecialchars($fornecedor['telefone'])?>" required onkeypress="mascara(this,telefone1)" maxlength="15">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?=htmlspecialchars($fornecedor['email'])?>" required>

            <label for="contato">Contato:</label>
            <input type="text" id="contato" name="contato" value="<?=htmlspecialchars($fornecedor['contato'])?>" required onkeypress="mascara(this,nome1)">

            <!--SE O USUÁRIO FOR ad, EXIBIR OPÇÃO DE ALTERAR senha-->

            <button type="submit" class="cadastrar">Alterar</button>
            <button type="reset" class="excluir">Cancelar</button>
            
        </form>
        <?php endif; ?>
        
        <a href="principal.php" class="back-btn">Voltar Ao Menu Principal</a>
</body>
</html>