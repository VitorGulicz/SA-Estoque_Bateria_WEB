<?php
session_start();
require_once 'conexao.php';

// Verifica se a conexão com o banco de dados foi estabelecida
if (!isset($pdo) || !$pdo) {
    die("Erro ao conectar ao banco de dados.");
}

// Inicializa a variável para evitar erros
$usuarios = [];

// Se o formulario foi enviado, busca o usuario pelo id ou nome

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['busca'])) {
    $busca = trim($_POST['busca']);

    // Verifica se a busca é numérica (ID) ou texto (nome)
    if (is_numeric($busca)) {
        $sql = "SELECT * FROM funcionario WHERE id_funcionario = :busca ORDER BY nome_funcionario ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca', $busca, PDO::PARAM_INT); 
    } else {
        $sql = "SELECT * FROM funcionario WHERE nome_funcionario LIKE :busca_nome ORDER BY nome_funcionario ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', "$busca%", PDO::PARAM_STR); 
       
    }  
}else{
    $sql = "SELECT * FROM funcionario ORDER BY nome_funcionario ASC";
    $stmt = $pdo->prepare($sql);
}
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔋 AutoBat - Buscar Funcionário</title>
    <style>
        /* Aplicando tema automotivo completo com cores de bateria */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #1a1a1a 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            color: #ffffff;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: linear-gradient(145deg, #2a2a2a, #1e1e1e);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3), 
                        inset 0 1px 0 rgba(255,255,255,0.1);
            border: 2px solid #FFD700;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #FFD700;
            font-size: 2.5em;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            background: linear-gradient(45deg, #FFD700, #FFA500);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .search-section {
            background: linear-gradient(145deg, #333333, #2a2a2a);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            border: 1px solid #FFD700;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }

        .search-section label {
            display: block;
            margin-bottom: 10px;
            color: #FFD700;
            font-weight: bold;
            font-size: 1.1em;
        }

        .search-form {
            display: flex;
            gap: 15px;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
        }

        input[type="text"] {
            padding: 12px 20px;
            border-radius: 10px;
            border: 2px solid #FFD700;
            background: linear-gradient(145deg, #2a2a2a, #1e1e1e);
            color: #ffffff;
            font-size: 16px;
            min-width: 250px;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #FF4444;
            box-shadow: 0 0 15px rgba(255, 212, 0, 0.3);
            transform: translateY(-2px);
        }

        input[type="text"]::placeholder {
            color: #888;
        }

        button {
            padding: 12px 25px;
            border-radius: 10px;
            border: none;
            background: linear-gradient(145deg, #FF4444, #CC0000);
            color: white;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(255, 68, 68, 0.3);
        }

        button:hover {
            background: linear-gradient(145deg, #CC0000, #AA0000);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(255, 68, 68, 0.4);
        }

        .table-container {
            background: linear-gradient(145deg, #2a2a2a, #1e1e1e);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
            border: 1px solid #FFD700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: transparent;
        }

        th {
            background: linear-gradient(145deg, #FFD700, #FFA500);
            color: #1a1a1a;
            padding: 15px 12px;
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #FF4444;
        }

        td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #444;
            color: #ffffff;
            font-size: 14px;
        }

        tr:nth-child(even) {
            background: rgba(255, 212, 0, 0.05);
        }

        tr:hover {
            background: linear-gradient(90deg, rgba(255, 212, 0, 0.1), rgba(255, 68, 68, 0.1));
            transform: scale(1.01);
            transition: all 0.3s ease;
        }

        .action-btn {
            padding: 8px 15px;
            border-radius: 8px;
            text-decoration: none;
            color: white;
            font-size: 12px;
            font-weight: bold;
            margin: 0 3px;
            display: inline-block;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .edit-btn {
            background: linear-gradient(145deg, #FFD700, #FFA500);
            color: #1a1a1a;
            box-shadow: 0 3px 6px rgba(255, 212, 0, 0.3);
        }

        .delete-btn {
            background: linear-gradient(145deg, #FF4444, #CC0000);
            box-shadow: 0 3px 6px rgba(255, 68, 68, 0.3);
        }

        .edit-btn:hover {
            background: linear-gradient(145deg, #FFA500, #FF8C00);
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(255, 212, 0, 0.4);
        }

        .delete-btn:hover {
            background: linear-gradient(145deg, #CC0000, #AA0000);
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(255, 68, 68, 0.4);
        }

        .no-results {
            text-align: center;
            padding: 40px;
            color: #FFD700;
            font-size: 1.2em;
            background: linear-gradient(145deg, #2a2a2a, #1e1e1e);
            border-radius: 15px;
            border: 1px solid #FFD700;
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

        .back-btn:hover {
            background: linear-gradient(145deg, #FFD700, #FFA500);
            color: #1a1a1a;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 212, 0, 0.3);
        }

        .header-icon {
            font-size: 1.2em;
            margin-right: 8px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin: 10px;
            }
            
            .search-form {
                flex-direction: column;
            }
            
            input[type="text"] {
                min-width: 200px;
            }
            
            table {
                font-size: 12px;
            }
            
            th, td {
                padding: 8px 4px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔋 Lista de Funcionários - VGM POWER</h2>
        
        <div class="search-section">
            <form method="POST" action="buscar_funcionario.php" class="search-form">
                <label for="busca">🔍 Buscar por ID ou Nome:</label>
                <input type="text" id="busca" name="busca" placeholder="Digite o ID ou nome do funcionário..." required>
                <button type="submit">⚡ Buscar</button>
            </form>
        </div>

        <?php if (!empty($usuarios)): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th><span class="header-icon">🆔</span>ID</th>
                            <th><span class="header-icon">👤</span>Nome</th>
                            <th><span class="header-icon">📄</span>CPF</th>
                            <th><span class="header-icon">🏠</span>Endereço</th>
                            <th><span class="header-icon">📞</span>Telefone</th>
                            <th><span class="header-icon">📧</span>Email</th>
                            <th><span class="header-icon">📅</span>Contratação</th>
                            <th><span class="header-icon">🔧</span>Cargo</th>
                            <th><span class="header-icon">💰</span>Salário</th>
                            <th><span class="header-icon">⚙️</span>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?=htmlspecialchars($usuario['id_funcionario']); ?></td>
                                <td><?=htmlspecialchars($usuario['nome_funcionario']); ?></td>
                                <td><?=htmlspecialchars($usuario['cpf']); ?></td>
                                <td><?=htmlspecialchars($usuario['endereco']); ?></td>
                                <td><?=htmlspecialchars($usuario['telefone']); ?></td>
                                <td><?=htmlspecialchars($usuario['email']); ?></td>
                                <td><?=htmlspecialchars($usuario['dataDeContratacao']); ?></td>
                                <td><?=htmlspecialchars($usuario['cargo']); ?></td>
                                <td>R$ <?=number_format($usuario['salario'], 2, ',', '.'); ?></td>
                                <td>
                                    <a href="alterar_funcionario.php?id=<?=$usuario['id_funcionario']; ?>" class="action-btn edit-btn">✏️ Alterar</a>
                                    <a href="excluir_funcionario.php?id=<?=$usuario['id_funcionario']; ?>" class="action-btn delete-btn" onclick="return confirm('⚠️ Tem certeza que deseja excluir este funcionário?')">🗑️ Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="no-results">
                <p>🔍 Nenhum funcionário encontrado.</p>
            </div>
        <?php endif; ?>

        <div style="text-align: center;">
            <a href="principal.php" class="back-btn">🏠 Voltar ao Menu Principal</a>
        </div>
    </div>
</body>
</html>
