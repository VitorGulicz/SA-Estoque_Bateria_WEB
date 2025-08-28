<?php 
session_start();
require_once 'conexao.php';
require_once 'menudrop.php';

//Verifica se o usuario tem permissão
//supondo que o perfil 1 seja o ADM
if($_SESSION['perfil']!= 1){
    echo "Acesso negado";
    exit();
}

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'],PASSWORD_DEFAULT);
    $id_perfil = $_POST['id_perfil'];

    $sql = "INSERT into usuario(nome, email, senha, id_perfil) values(:nome, :email, :senha, :id_perfil)";
    $stmt = $pdo ->prepare($sql);
    $stmt->bindparam(':nome', $nome);
    $stmt->bindparam(':email', $email);
    $stmt->bindparam(':senha', $senha);
    $stmt->bindparam(':id_perfil', $id_perfil);

    if($stmt->execute()){
        echo "<script>alert('Usuario Cadastrado com sucesso');</script>";
    }else{
        echo "<script>alert('Erro ao cadastrar usuario');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuario</title>
    <link rel="stylesheet" href="CSS\styles.css">
    <script src="JS\mascara.js"></script>
    <link rel="stylesheet" href="CSS\tabela.css">
</head>
<body>
    <h2>Cadastrar Usuario</h2>
    <form action="cadastro_usuario.php" method="POST">
        <label for="nome">Nome: </label>
        <input type="text" id="nome" name="nome" required onkeypress="mascara(this,nome1)">

        <label for="email">Email: </label>
        <input type="email" id="email" name="email" required>

        <label for="senha">Senha: </label>
        <input type="password" id="senha" name="senha" required>

        <label for="id_perfil">Perfil: </label>
        <select id="id_perfil" name="id_perfil">
            <option value="1">Administrador</option>
            <option value="2">Secretaria</option>
            <option value="3">Almoxarife</option>
            <option value="4">Cliente</option>
</select>
        <button type="submit" class="cadastrar">Cadastrar</button>
        <button type="submit" class="excluir">Cancelar</button>
</form>
    <a href="principal.php" class="voltar">Voltar</a>
    </body>
</html>