<?php 
session_start();
require_once 'conexao.php';
require_once 'menudrop.php';


if($_SESSION['perfil']!= 1){
    echo "Acesso negado";
    exit();
}

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $nome_cliente = $_POST['nome_cliente'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $cpf = $_POST['cpf'];

    $sql = "INSERT into cliente(nome_cliente, endereco, telefone, email, cpf) values(:nome_cliente, :endereco, :telefone, :email, :cpf)";
    $stmt = $pdo ->prepare($sql);
    $stmt->bindparam(':nome_cliente', $nome_cliente);
    $stmt->bindparam(':endereco', $endereco);
    $stmt->bindparam(':telefone', $telefone);
    $stmt->bindparam(':email', $email);
    $stmt->bindparam(':cpf', $cpf);

    if($stmt->execute()){
        echo "<script>alert('Cliente Cadastrado com sucesso');</script>";
    }else{
        echo "<script>alert('Erro ao cadastrar Cliente');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Cliente</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <script src="../JS/mascara.js"></script>
    <link rel="stylesheet" href="../CSS/cadastro.css">
</head>
<body>
    <h2>Cadastrar Cliente</h2>
    <form action="cadastro_cliente.php" method="POST">

        <label for="nome_cliente">Nome do Cliente: </label>
        <input type="text" id="nome_cliente" name="nome_cliente" required onkeypress="mascara(this,nome1)">

        <label for="endereco">Endereço do Cliente: </label>
        <input type="text" id="endereco" name="endereco" required>

        <label for="telefone">Telefone do Cliente: </label>
        <input type="text" id="telefone" name="telefone" required onkeypress="mascara(this,telefone1)" maxlength="15" >

        <label for="email">Email do Cliente: </label>
        <input type="email" id="email" name="email" required>

        <label for="cpf">CPF do Cliente: </label>
        <input type="text" id="cpf" name="cpf" required onkeypress="mascara(this,cpf1)" maxlength="14">

        <button type="submit" class="cadastrar">Cadastrar</button>
        <button type="submit" class="excluir">Cancelar</button>
</form>
    <a href="principal.php" class="voltar">Voltar</a>
    </body>
</html>