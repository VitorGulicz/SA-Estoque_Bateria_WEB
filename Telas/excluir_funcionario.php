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
    <title>🔋 Excluir Funcionário - VGM POWER</title>
    <link rel="stylesheet" href="excluir_funcionario.css">
    <link rel="stylesheet" href="../CSS/excluir.css">
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

    <a href="principal.php" class="back-btn">Voltar ao Menu Principal</a>
</body>
</html>
