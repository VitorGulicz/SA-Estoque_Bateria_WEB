<?php
session_start();
require_once 'conexao.php';
require_once 'menudrop.php';

// verifica se o usuário tem permissão para acessar a página
if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2) {
    echo "<script>alert('Acesso negado.');window.location.href='principal.php';</script>";
    exit();
}
// Inicializa a variável do usuário 
$usuario = null;

// Busca todos os usuarios cadastrados em ordem alfabética
$sql = "SELECT * FROM funcionario ORDER BY nome_funcionario ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Se um id for enviado via GET, busca o usuário correspondente
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_usuario = $_GET['id'];

    // exclui o usuario do banco de dados
    $sql = "DELETE FROM funcionario WHERE id_funcionario = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();

    // Se não encontrar o usuário, exibe um alerta
    if ($stmt->rowCount() > 0) {
        echo "<script>alert('Funcionario excluído com sucesso!');window.location.href='excluir_funcionario.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir funcionario.');</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔋 Excluir Funcionário - AutoBat Pro</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #1a1a1a 100%);
            margin: 0;
            padding: 20px;
            text-align: center;
            min-height: 100vh;
            color: #fff;
        }

        /* Adicionado header com tema automotivo */
        .header-container {
            background: linear-gradient(45deg, #000000, #FFD700, #DC143C);
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 8px 25px rgba(255, 215, 0, 0.3);
        }

        h2 {
            margin: 0;
            color: #000;
            font-size: 2.2em;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(255,255,255,0.3);
        }

        .subtitle {
            color: #000;
            font-size: 1.1em;
            margin-top: 5px;
            font-weight: 500;
        }

        /* Estilização da tabela com tema automotivo */
        table {
            margin: 0 auto;
            border-collapse: collapse;
            width: 95%;
            background: linear-gradient(145deg, #2a2a2a, #1e1e1e);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(255, 215, 0, 0.2);
            border: 2px solid #FFD700;
        }

        th, td {
            padding: 15px 12px;
            text-align: center;
            border-bottom: 1px solid #444;
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

        /* Ícones temáticos para cada coluna */
        th:nth-child(1)::before { content: "🆔 "; }
        th:nth-child(2)::before { content: "👤 "; }
        th:nth-child(3)::before { content: "📄 "; }
        th:nth-child(4)::before { content: "🏠 "; }
        th:nth-child(5)::before { content: "📞 "; }
        th:nth-child(6)::before { content: "📧 "; }
        th:nth-child(7)::before { content: "📅 "; }
        th:nth-child(8)::before { content: "🔧 "; }
        th:nth-child(9)::before { content: "💰 "; }
        th:nth-child(10)::before { content: "⚡ "; }

        tr:nth-child(even) {
            background: linear-gradient(90deg, #2a2a2a, #333333);
        }

        tr:nth-child(odd) {
            background: linear-gradient(90deg, #1e1e1e, #2a2a2a);
        }

        tr:hover {
            background: linear-gradient(90deg, #FFD700, #FFA500) !important;
            color: #000 !important;
            transform: scale(1.02);
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.4);
        }

        td {
            color: #fff;
            font-weight: 500;
        }

        /* Botão de exclusão com tema automotivo */
        a.delete-btn {
            padding: 8px 15px;
            border-radius: 8px;
            text-decoration: none;
            color: #fff;
            background: linear-gradient(45deg, #DC143C, #8B0000);
            font-size: 13px;
            font-weight: bold;
            margin: 0 2px;
            display: inline-block;
            transition: all 0.3s ease;
            border: 2px solid #DC143C;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }

        a.delete-btn::before {
            content: "🗑️ ";
        }

        a.delete-btn:hover {
            background: linear-gradient(45deg, #FF0000, #DC143C);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 20, 60, 0.4);
            border-color: #FF0000;
        }

        /* Mensagem quando não há funcionários */
        p {
            color: #FFD700;
            font-size: 18px;
            font-weight: bold;
            background: linear-gradient(145deg, #2a2a2a, #1e1e1e);
            padding: 30px;
            border-radius: 15px;
            border: 2px solid #FFD700;
            margin: 20px auto;
            max-width: 500px;
        }

        p::before {
            content: "⚠️ ";
            font-size: 24px;
        }

        /* Botão voltar com tema automotivo */
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

        a.back-btn::before {
            content: "🔙 ";
        }

        a.back-btn:hover {
            background: linear-gradient(45deg, #FFD700, #FFA500);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 215, 0, 0.4);
        }

        /* Efeitos visuais adicionais */
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(255, 215, 0, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(255, 215, 0, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 215, 0, 0); }
        }

        .header-container {
            animation: pulse 3s infinite;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            table {
                font-size: 12px;
                width: 100%;
            }
            
            th, td {
                padding: 8px 4px;
            }
            
            h2 {
                font-size: 1.8em;
            }
        }
    </style>
</head>
<body>
    <div class="header-container">
        <h2>🔋 Excluir Funcionário</h2>
        <div class="subtitle">⚡ AutoBat Pro - Sistema de Gestão ⚡</div>
    </div>

    <?php if(!empty($usuarios)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Cpf</th>
                    <th>Endereço</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>Data De Contratação</th>
                    <th>Cargo</th>
                    <th>Salario</th>
                    <th>Ações</th>
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
                        <td><?=htmlspecialchars($usuario['salario']); ?></td>
                        <td>
                            <a href="excluir_funcionario.php?id=<?= $usuario['id_funcionario']; ?>" class="delete-btn" onclick="return confirm('Tem certeza que deseja excluir este funcionário?');">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum funcionário encontrado no sistema.</p>
    <?php endif; ?>

    <a href="principal.php" class="back-btn">🏠 Voltar ao Menu Principal</a>

</body>
</html>
