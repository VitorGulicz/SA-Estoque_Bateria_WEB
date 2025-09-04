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
$cliente = null;

//SE O FORMULARIO FOR ENVIADO, BUSCA O USUARIO PELO ID OU PELO NOME
if ($_SERVER["REQUEST_METHOD"]=="POST") {
    if (!empty($_POST['busca_cliente'])) {
        $busca = trim($_POST["busca_cliente"]);

    if(is_numeric($busca)) {
        $sql = "SELECT * FROM cliente WHERE id_cliente = :busca";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca' ,$busca,PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM cliente WHERE nome_cliente LIKE :busca_nome";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', "$busca%", PDO::PARAM_STR);
    }
    $stmt->execute();
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    // SE O USUÁRIO NÃO FOR ENCONTRADO, EXIBE UM ALERTA

    if(!$cliente){
        echo "<script>alert('Cliente não encontrado!');</script>";
    }
}
    } elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (!empty($_GET['id'])) {
            $busca = trim($_GET["id"]);

        if(is_numeric($busca)) {
            $sql = "SELECT * FROM cliente WHERE id_cliente = :busca";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':busca' ,$busca,PDO::PARAM_INT);
        } else {
            $sql = "SELECT * FROM cliente WHERE nome_cliente LIKE :busca_nome";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':busca_nome', "$busca%", PDO::PARAM_STR);
        }
        $stmt->execute();
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(!$cliente){
            echo "<script>alert('Cliente não encontrado!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Cliente</title>
    <link rel="stylesheet" href="../CSS/cadastro.css">
<!--Certifique-se de que o Java script está sendo carregado corretamente-->
<script src="../JS/scripts.js"></script>
<script src="../JS/mascara.js"></script>
</head>
<body>
<h2>Alterar cliente</h2>
<!--FORMULÁRIO PARA BUSCAR cliente-->
    <form action="alterar_cliente.php" method="POST">
        <label for="busca_cliente">Digite o id ou NOME do cliente:</label>
        <input type="text" id="busca_cliente" name="busca_cliente" required onkeyup="buscarSugestoes()" >
        <div id="sugestoes">
        </div>
        <button type="submit">Buscar</button>
    </form>
</br>
    <?php if ($cliente): ?>
        <form action="processa_alteracao_cliente.php" method="POST">
            <input type="hidden" name="id_cliente" value="<?=htmlspecialchars($cliente['id_cliente'])?>">

            <label for="nome_cliente">Nome do cliente:</label>
            <input type="text" id="nome_cliente" name="nome_cliente" value="<?=htmlspecialchars($cliente['nome_cliente'])?>" required onkeypress="mascara(this,nome1)">

            <label for="endereco">Endereço:</label>
            <input type="text" id="endereco" name="endereco" value="<?=htmlspecialchars($cliente['endereco'])?>" required >
            
            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" value="<?=htmlspecialchars($cliente['telefone'])?>" required onkeypress="mascara(this,telefone1)" maxlength="15">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?=htmlspecialchars($cliente['email'])?>" required>

            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" value="<?=htmlspecialchars($cliente['cpf'])?>" required onkeypress="mascara(this,cpf1)" maxlength="14">

            <!--SE O USUÁRIO FOR ad, EXIBIR OPÇÃO DE ALTERAR senha-->

            <button type="submit" class="cadastrar">Alterar</button>
            <button type="reset" class="excluir">Cancelar</button>
            
        </form>
        <?php endif; ?>
        
        <a href="principal.php" class="back-btn">Voltar ao Menu Principal</a>
</body>
</html>