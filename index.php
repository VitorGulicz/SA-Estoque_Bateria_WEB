<?php
session_start();
require 'conexao.php';

if($_SERVER["REQUEST_METHOD"] =="POST"){
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuario WHERE usuario=:usuario";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario',$usuario);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if($usuario && password_verify($senha,$usuario['senha'])){
        //LOGIN BEM SUCEDIDO, DEFINE VARIAVEIS DE SESSÃO

    
        $_SESSION['usuario'] = $usuario['usuario'];

        $_SESSION['COD_USER'] = $usuario['COD_USER'];

        // VERIFICA SE A SENHA É TEMPORARIA
        if($usuario['senha_temporaria']){
            // REDIRECIONA PARA A PAGINA "senha_temporaria"
            header("Location: alterar_senha.php");
            exit();
        } else {
            //REDIRECIONA PARA A PAGINA PRINCIPAL
            header("Location: principal.php");
            exit();
        }
    }else{
        //LOGIN INVALIDO
        echo "<script>alert('E-mail ou senha incorretos'); window.location.href='index.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="tabela.css">
</head>
<body>
    <h2>Login:</h2>
    <form action="index.php" method="POST">
        <label for="usuario">Usuario</label>
        <input type="text" id="usuario" name="usuario" required>
        </br>

        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha" required>
        </br>

        <button type="submit">Entrar</button>
    </form>

    <p><a href="recuperar_senha.php" class="voltar">Esqueci minha senha</a>
    <adress><h2>Vitor Gulicz | Senai Norte </h2></adress>
</body>
</html>