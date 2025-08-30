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
            content: '👤 ';
            margin-right: 10px;
        }

        h2::after {
            content: ' 🔋';
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

        label[for="nome"]::before { content: '👤 '; margin-right: 5px; }
        label[for="email"]::before { content: '📧 '; margin-right: 5px; }
        label[for="senha"]::before { content: '🔐 '; margin-right: 5px; }
        label[for="id_perfil"]::before { content: '🔧 '; margin-right: 5px; }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
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
        input[type="password"]:focus,
        select:focus {
            border-color: #ffc107;
            box-shadow: 0 0 10px rgba(255, 193, 7, 0.3);
            outline: none;
            background: linear-gradient(145deg, #2c2c2c, #1a1a1a);
        }

        /* Styling select dropdown to match theme */
        select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23ffc107' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            padding-right: 40px;
        }

        select option {
            background: #2c2c2c;
            color: #ffffff;
            padding: 10px;
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

        /* Fixed button styling for submit and reset */
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

        button.excluir {
            background: linear-gradient(145deg, #6c757d, #5a6268);
            color: white;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }

        button.excluir:hover {
            background: linear-gradient(145deg, #5a6268, #495057);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
        }

        button.excluir::before {
            content: '❌ ';
        }

        /* Enhanced back button styling */
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
            
            button, a.voltar {
                width: 100%;
                margin: 10px 0;
                text-align: center;
            }
        }
    </style>

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
        <!-- Fixed button type from submit to button for cancel -->
        <button type="button" class="excluir" onclick="document.getElementById('nome').value=''; document.getElementById('email').value=''; document.getElementById('senha').value=''; document.getElementById('id_perfil').selectedIndex=0;">Cancelar</button>
    </form>
    <a href="principal.php" class="back-btn">🏠 Voltar ao Menu Principal</a>
</body>
</html>
