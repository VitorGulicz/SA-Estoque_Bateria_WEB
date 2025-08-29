<?php
session_start();
require_once 'conexao.php';
require_once 'menudrop.php';

// 
if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2) {
    echo "<script>alert('Acesso negado.');window.location.href='principal.php';</script>";
    exit();
}

$usuario = null;

// Se o formulario foi enviado, busca o usuario pelo id ou nome

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['busca_funcionario'])) {
    $busca = trim($_POST['busca_funcionario']);

    // Verifica se a busca é numérica (ID) ou texto (nome)
    if (is_numeric($busca)) {
        $sql = "SELECT * FROM funcionario WHERE id_funcionario = :busca";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca', $busca, PDO::PARAM_INT); 
    } else {
        $sql = "SELECT * FROM funcionario WHERE nome_funcionario LIKE :busca_nome";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR); 
    } 
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se não encontrar o usuário, exibe um alerta
    if (!$usuario) {
        echo "<script>alert('Funcionario não encontrado!');</script>";
    }
}
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Funcionario - AutoBaterias Pro</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Certifique-se de que o Javascript está sendo carregado com sucesso -->
    <script src="scripts.js"></script>
    <script src="mascaras.js"></script>
    <style>
        /* Aplicando tema completo de loja de baterias automotivas */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #1a1a1a 100%);
            color: #ffffff;
            min-height: 100vh;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #FFD700;
            font-size: 2.5em;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            background: linear-gradient(45deg, #FFD700, #FFA500);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        h2::before {
            content: "🔋 ";
            -webkit-text-fill-color: #FFD700;
        }

        h2::after {
            content: " ⚡";
            -webkit-text-fill-color: #FF4444;
        }

        form {
            background: linear-gradient(145deg, #2a2a2a, #1e1e1e);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            margin-bottom: 30px;
            border: 2px solid #FFD700;
            position: relative;
            overflow: hidden;
        }

        form::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #FFD700, transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #FFD700;
            font-weight: bold;
            font-size: 1.1em;
        }

        label::before {
            content: "🔧 ";
            margin-right: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="number"] {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 2px solid #444;
            border-radius: 8px;
            background: linear-gradient(145deg, #333, #2a2a2a);
            color: #ffffff;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="date"]:focus,
        input[type="number"]:focus {
            border-color: #FFD700;
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.3);
            transform: translateY(-2px);
        }

        #sugestoes {
            background: #2a2a2a;
            border: 1px solid #FFD700;
            border-radius: 8px;
            margin-bottom: 15px;
            max-height: 200px;
            overflow-y: auto;
        }

        button {
            background: linear-gradient(45deg, #FFD700, #FFA500);
            color: #000;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: bold;
            margin-right: 10px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
        }

        button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255,255,255,0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.3s, height 0.3s;
        }

        button:hover::before {
            width: 300px;
            height: 300px;
        }

        button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 215, 0, 0.4);
        }

        button[type="submit"]::before {
            content: "⚡ ";
        }

        button[type="reset"] {
            background: linear-gradient(45deg, #FF4444, #CC0000);
            color: white;
        }

        button[type="reset"]::before {
            content: "🔄 ";
        }

        a.back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            background: linear-gradient(45deg, #666, #444);
            color: white;
            font-weight: bold;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        a.back-btn::before {
            content: "🏠 ";
            margin-right: 5px;
        }

        a.back-btn:hover {
            background: linear-gradient(45deg, #777, #555);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        /* Estilo especial para campos específicos */
        input[name="nome_funcionario"] + label::before { content: "👤 "; }
        input[name="cpf"] + label::before { content: "📄 "; }
        input[name="endereco"] + label::before { content: "🏠 "; }
        input[name="telefone2"] + label::before { content: "📞 "; }
        input[name="email"] + label::before { content: "📧 "; }
        input[name="dataDeContratacao"] + label::before { content: "📅 "; }
        input[name="cargo"] + label::before { content: "💼 "; }
        input[name="salario"] + label::before { content: "💰 "; }

        /* Responsividade */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            h2 {
                font-size: 2em;
            }
            
            form {
                padding: 20px;
            }
        }

        /* Efeito de brilho no fundo */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 80%, rgba(255, 215, 0, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(255, 68, 68, 0.1) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
        }
    </style>
</head>
<body>
<h2>Alterar Funcionario</h2>
    <!-- Formulário de busca -->
    <form method="POST" action="alterar_funcionario.php">
        <label for="busca_funcionario">Buscar por ID ou Nome:</label>
        <input type="text" id="busca_funcionario" name="busca_funcionario" required onkeyup="BuscarSugestoes()">

        <div id="sugestoes"></div>

        <button type="submit">Buscar</button>
        </form>

        <?php if ($usuario): ?>
            <form method="POST" action="processa_alteracao_funcionario.php">
                <input type="hidden" name="id_funcionario" value="<?=htmlspecialchars($usuario['id_funcionario']); ?>">

                <label for="nome">Nome:</label>
                <input type="text" id="nome_funcionario" name="nome_funcionario" value="<?=htmlspecialchars($usuario['nome_funcionario']); ?>" required onkeypress ="mascara(this, nome)">
                
                <label for="cpf">CPF:</label>
                <input type="text" id="cpf" name="cpf" value="<?=htmlspecialchars($usuario['cpf']); ?>" required onkeypress ="mascara(this, cpf)" maxlength="14">

                <label for="endereco">Endereço:</label>
                <input type="text" id="endereco" name="endereco" value="<?=htmlspecialchars($usuario['endereco']); ?>" required>

                <label for="telefone2">Telefone:</label>
                <input type="text" id="telefone2" name="telefone2" value="<?=htmlspecialchars($usuario['telefone']); ?>" required onkeypress ="mascara(this, telefone1)"maxlength="15">

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?=htmlspecialchars($usuario['email']); ?>" required>

                <label for="dataDeContratacao">Data De Contratação:</label>
                <input type="date" id="dataDeContratacao" name="dataDeContratacao" value="<?=htmlspecialchars($usuario['dataDeContratacao']); ?>" required>

                <label for="cargo">Cargo:</label>
                <input type="text" id="cargo" name="cargo" value="<?=htmlspecialchars($usuario['cargo']); ?>" required>

                <label for="salario">Salario:</label>
                <input type="number" step="0.01" id="salario" name="salario" value="<?=htmlspecialchars($usuario['salario']); ?>" required>


                <button type="submit">Alterar</button>
                <button type="reset">Cancelar</button>
                </form>
        <?php endif; ?>
        <a href="principal.php" class="back-btn">Voltar</a>
</div>
    
</body>
</html>
