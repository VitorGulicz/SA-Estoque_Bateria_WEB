<?php 

session_start();
require_once 'conexao.php';
require_once 'menudrop.php';


// verifica se o usuario tem permissao 
if ($_SERVER["REQUEST_METHOD"]=="POST") {
    $nome = $_POST['nome2'];
    $cpf = $_POST['cpf'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone2'];
    $email = $_POST['email'];
    $data = $_POST['data'];
    $cargo = $_POST['cargo'];
    $salario = $_POST['salario'];

    $sql = "INSERT INTO funcionario (nome_funcionario, cpf, endereco, telefone, email, dataDeContratacao, cargo, salario) 
            VALUES (:nome_funcionario, :cpf, :endereco, :telefone, :email, :dataDeContratacao, :cargo, :salario)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome_funcionario', $nome);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':dataDeContratacao', $data);
    $stmt->bindParam(':cargo', $cargo);
    $stmt->bindParam(':salario', $salario);


    if($stmt->execute()) {
        echo "<script>alert('Funcionario cadastrado com sucesso!');</script>";
    }else{
        echo "<script>alert('Erro ao cadastrar funcionario.');</script>";
    }

}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VGM Power - Cadastrar Funcionário</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <link rel="stylesheet" href="../CSS/tabela.css">
    <link rel="stylesheet" href="../CSS/cadastro_funcionario.css">
    <script src="../JS/mascara.js"></script>
    <link rel="stylesheet" href="../CSS/cadastro.css">
</head>
<body>
    <h2>Cadastro de Funcionario</h2>
    <form method="POST" action="cadastro_funcionario.php">
        <label for="nome">Nome:</label>
        <input type="text" id="nome2" name="nome2" required onkeypress ="mascara(this, nome)">
        
      <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf" required maxlength="14" onkeypress ="mascara(this, mascaraCPF)">
        

        <label for="endereco">Endereço:</label>
        <input type="text" id="endereco" name="endereco" required>
        
        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone2" name="telefone2" required maxlength="15" onkeypress="mascara(this, mascaraTelefone)">

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="data">Data de contratação:</label>
        <input type="date" id="data" name="data" required>

        <label for="cargo">Cargo:</label>
        <input type="text" id="cargo" name="cargo" required>
        

       <label for="salario">Salário:</label>
        <input type="number" id="salario" name="salario" required>
 
      
        <button type="submit">Cadastrar</button>
        <button type="reset">Cancelar</button>
    </form>


    <a href="principal.php" class="back-btn">Voltar ao Menu Principal</a>
</body>
</html>


