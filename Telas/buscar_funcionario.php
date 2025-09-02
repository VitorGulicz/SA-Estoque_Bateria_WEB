<?php
session_start();
require_once 'conexao.php';
require_once 'menudrop.php';

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
} else {
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
    <title>VGM POWER - Buscar Funcionário</title>
    <link rel="stylesheet" href="css/buscar_funcionario.css">
    <link rel="stylesheet" href="../CSS/tudo.css">
</head>
<body>
    <div class="container">
        <h2>Lista de Funcionários - VGM POWER</h2>
        
        <div class="search-section">
            <form method="POST" action="buscar_funcionario.php" class="search-form">
                <label for="busca">Buscar por ID ou Nome:</label>
                <input type="text" id="busca" name="busca" placeholder="Digite o ID ou nome do funcionário..." required>
                <button type="submit">Buscar</button>
            </form>
        </div>

        <?php if (!empty($usuarios)): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Endereço</th>
                            <th>Telefone</th>
                            <th>Email</th>
                            <th>Contratação</th>
                            <th>Cargo</th>
                            <th>Salário</th>
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
                                <td>R$ <?=number_format($usuario['salario'], 2, ',', '.'); ?></td>
                                <td>
                                    <a href="alterar_funcionario.php?id=<?=$usuario['id_funcionario']; ?>" class="action-btn edit-btn ">Alterar</a>
                                    <a href="excluir_funcionario.php?id=<?=$usuario['id_funcionario']; ?>" class="action-btn delete-btn" onclick="return confirm('⚠️ Tem certeza que deseja excluir este funcionário?')">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="no-results">
                <p>Nenhum funcionário encontrado.</p>
            </div>
        <?php endif; ?>

        <div style="text-align: center;">
            <a href="principal.php" class="back-btn">Voltar ao Menu Principal</a>
        </div>
    </div>
</body>
</html>
