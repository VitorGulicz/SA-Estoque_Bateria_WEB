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
    $sql = "INSERT INTO funcionario (nome_funcionario, cpf, endereco, telefone, email, dataDeContratacao, cargo, salario) VALUES (:nome_funcionario, :cpf, :endereco, :telefone, :email, :dataDeContratacao, :cargo, :salario)";
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
};
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔋 AutoBat - Cadastrar Funcionário</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <script src="../JS/mascara.js"></script>
    <link rel="stylesheet" href="../CSS/tabela.css">

    <style>
        /* Aplicando tema de loja de bateria automotiva */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #2c2c2c 50%, #1a1a1a 100%);
            color: #ffffff;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            background-attachment: fixed;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(255, 193, 7, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(220, 53, 69, 0.1) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
        }

        h2 {
            text-align: center;
            color: #ffc107;
            font-size: 2.5em;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            font-weight: bold;
        }

        h2::before {
            content: '🔋 ';
            margin-right: 10px;
        }

        h2::after {
            content: ' ⚡';
            margin-left: 10px;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            background: linear-gradient(145deg, #2c2c2c, #1e1e1e);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 
                0 15px 35px rgba(0,0,0,0.3),
                inset 0 1px 0 rgba(255,255,255,0.1);
            border: 2px solid #ffc107;
        }

        label {
            display: block;
            margin-top: 20px;
            margin-bottom: 8px;
            color: #ffc107;
            font-weight: bold;
            font-size: 1.1em;
        }

        label::before {
            content: '🔧 ';
            margin-right: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="number"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #444;
            border-radius: 8px;
            background: linear-gradient(145deg, #1a1a1a, #2c2c2c);
            color: #ffffff;
            font-size: 1em;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="date"]:focus,
        input[type="number"]:focus {
            border-color: #ffc107;
            box-shadow: 0 0 10px rgba(255, 193, 7, 0.3);
            outline: none;
            background: linear-gradient(145deg, #2c2c2c, #1a1a1a);
        }

        button {
            padding: 12px 25px;
            border-radius: 8px;
            border: none;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            margin: 20px 10px 0 0;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        button[type="submit"] {
            background: linear-gradient(145deg, #dc3545, #c82333);
            color: white;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }

        button[type="submit"]:hover {
            background: linear-gradient(145deg, #c82333, #a71e2a);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
        }

        button[type="submit"]::before {
            content: '⚡ ';
        }

        button[type="reset"] {
            background: linear-gradient(145deg, #6c757d, #5a6268);
            color: white;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }

        button[type="reset"]:hover {
            background: linear-gradient(145deg, #5a6268, #495057);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
        }

        button[type="reset"]::before {
            content: '🔄 ';
        }

        .back-btn {
            display: inline-block;
            margin-top: 30px;
            padding: 15px 30px;
            border-radius: 10px;
            text-decoration: none;
            background: linear-gradient(145deg, #333333, #2a2a2a);
            color: #FFD700;
            font-weight: bold;
            border: 2px solid #FFD700;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        a.back-btn:hover {
            background: linear-gradient(145deg, #e0a800, #d39e00);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 193, 7, 0.4);
        }

        a.back-btn::before {
            content: ' ';
            margin-right: 8px;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            form {
                margin: 10px;
                padding: 20px;
            }
            
            h2 {
                font-size: 2em;
            }
            
            button, a.back-btn {
                width: 100%;
                margin: 10px 0;
                text-align: center;
            }
        }
    </style>

</head>
<body>
    <h2>Cadastro de Funcionário</h2>
    <form method="POST" action="cadastro_funcionario.php">

        <label for="nome">Nome:</label>
        <input type="text" id="nome2" name="nome2" required onkeypress ="mascara(this, nome1)">

        <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf" required maxlength="14" onkeypress ="mascara(this, mascaraCPF)">

        <label for="endereco">Endereço:</label>
        <input type="text" id="endereco" name="endereco" required>
        
        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone2" name="telefone2" required onkeypress ="mascara(this, mascaraTelefone)" maxlength="15">

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="data">Data de contratação:</label>
        <input type="date" id="data" name="data" required>

        <label for="cargo">Cargo:</label>
        <input type="text" id="cargo" name="cargo" required>

        <label for="salario">Salario:</label>
        <input type="number" id="salario" name="salario" required>
        
        
        <button type="submit">Cadastrar</button>
        <button type="reset">Cancelar</button>
    </form>
    <a href="principal.php" class="back-btn">🏠 Voltar ao Menu Principal</a>
</body>
</html>
