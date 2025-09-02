<?php 
session_start();
require_once 'conexao.php';
require_once 'menudrop.php';


// verifica se o usuario tem permissao 
if($_SESSION['perfil']!= 1) {
    echo "Acesso negado. ";
    exit();
}

if ($_SERVER["REQUEST_METHOD"]=="POST") {
    $nome = $_POST['nome2'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone2'];
    $email = $_POST['email'];
    $sql = "INSERT INTO funcionario (nome_funcionario, endereco, telefone, email) VALUES (:nome_funcionario, :endereco, :telefone, :email)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome_funcionario', $nome);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':email', $email);

    if($stmt->execute()) {
        echo "<script>alert('Funcionario cadastrado com sucesso!');</script>";
    }else{
        echo "<script>alert('Erro ao cadastrar funcionario.');</script>";
    }
};
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Funcionario</title>
    <link rel="stylesheet" href="styles.css">
    <script src="mascaras.js"></script>

    <style>
    button {
            padding: 6px 12px;
            border-radius: 6px;
            border: none;
            background-color: #3498db;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #2980b9;
        }
        a.back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            background-color: #3498db;
            color: white;
        }

        a.back-btn:hover {
            background-color: #2980b9;
        }
        
        </style>

</head>
<body>
    <h2>Cadastro de Funcionario</h2>
    <form method="POST" action="cadastro_funcionario.php">
        <label for="nome">Nome:</label>
        <input type="text" id="nome2" name="nome2" required onkeypress ="mascara(this, nome)">
        
        <label for="endereco">Endereço:</label>
        <input type="text" id="endereco" name="endereco" required>
        
        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone2" name="telefone2" required onkeypress ="mascara(this, telefone1)" maxlength="15">

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <button type="submit">Cadastrar</button>
        <button type="reset">Cancelar</button>
    </form>
    <a href="principal.php" class="back-btn">Voltar</a>
</body>
</html>