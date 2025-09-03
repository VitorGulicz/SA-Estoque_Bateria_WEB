<?php
session_start();
require_once 'conexao.php';
require_once 'menudrop.php';

if($_SESSION['perfil']!=1 && $_SESSION['perfil']!=2) {
    echo "<script>alert('Acesso Negado!');window.location.href='principal.php';</script>";
    exit();
}

// Inicializa a variável
$usuarios = [];

// Busca pelo ID ou Nome
if ($_SERVER["REQUEST_METHOD"]=="POST" && !empty($_POST["busca"])) {
    $busca = trim($_POST["busca"]);
    if(is_numeric($busca)) {
        $sql = "SELECT * FROM usuario WHERE id_usuario = :busca ORDER BY nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM usuario WHERE nome LIKE :busca_nome ORDER BY nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', "$busca%", PDO::PARAM_STR);
    }
} else {
    $sql = "SELECT * FROM usuario ORDER BY nome ASC";
    $stmt = $pdo->prepare($sql);
}

$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Usuario - AutoBat Pro</title>
    <!-- Link para CSS externo -->
    <link rel="stylesheet" href="../CSS/busca.css">
</head>
<body>
    <div class="container">
    <h2>Lista de USUARIOS - VGM POWER</h2>


    <!-- FORMULÁRIO PARA BUSCAR USUARIOS -->
    <div class="search-section">
    <form action="buscar_usuario.php" method="POST">
        <label for="busca">Digite o ID ou Nome (opcional):</label>
        <input type="text" id="busca" name="busca" placeholder="Ex: 1 ou João Silva">
        <button type="submit">Buscar Usuário</button>
    </form>
    </div>

    <?php if(!empty($usuarios)): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>ID Perfil</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($usuarios as $usuario): ?>
                        <tr>
                            <td><?=htmlspecialchars($usuario['id_usuario'])?></td>
                            <td><?=htmlspecialchars($usuario['nome'])?></td>
                            <td><?=htmlspecialchars($usuario['email'])?></td>
                            <td><?=htmlspecialchars($usuario['id_perfil'])?></td>
                            <td>
                                <a href="alterar_usuario.php?id=<?=htmlspecialchars($usuario['id_usuario'])?>" class="action-btn edit-btn ">
                                <a href="excluir_usuario.php?id=<?= htmlspecialchars($usuario['id_usuario']) ?>" class="action-btn delete-btn"  onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="no-results">🔋 Nenhum usuário encontrado no sistema Vgm power Pro.</p>
    <?php endif; ?>

    <a href="principal.php" class="back-btn">Voltar ao Menu Principal</a>
    </div>
</body>
</html>
