<?php 
session_start();
require_once 'conexao.php';
require_once 'menudrop.php';

// Verifica se o usuario tem permissão (perfil 1 = ADM)
if($_SESSION['perfil'] != 1){
    echo "Acesso negado";
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $id_perfil = $_POST['id_perfil'];

    $sql = "INSERT INTO usuario(nome, email, senha, id_perfil) VALUES(:nome, :email, :senha, :id_perfil)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);
    $stmt->bindParam(':id_perfil', $id_perfil);

    if($stmt->execute()){
        echo "<script>alert('Usuario Cadastrado com sucesso');</script>";
    } else {
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

    <!-- CSS externo -->
    
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- JS externo -->
    <script src="../JS/mascara.js"></script>
    <link rel="stylesheet" href="../CSS/cadastro.css">
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
        <select id="id_perfil" name="id_perfil" class="select2">
            <option value="1">Administrador</option>
            <option value="2">Secretaria</option>
            <option value="3">Almoxarife</option>
            <option value="4">Cliente</option>
            <option value="5">Funcionario</option>
        </select>
        
        <button type="submit" class="cadastrar">Cadastrar</button>
        <button type="button" class="excluir" onclick="
            document.getElementById('nome').value='';
            document.getElementById('email').value='';
            document.getElementById('senha').value='';
            document.getElementById('id_perfil').selectedIndex=0;
        ">Cancelar</button>
    </form>
    <a href="principal.php" class="back-btn">Voltar ao Menu Principal</a>
    <script>
$(document).ready(function() {
    $('#id_perfil').select2({
        placeholder: "Escolha perfil",
        allowClear: true
    });
});
</script>
</body>
</html>
