<?php
session_start();
require_once 'conexao.php';
require_once 'menudrop.php';

if($_SESSION['perfil']!=1 && $_SESSION['perfil']!=2) {
    echo "<script>alert('Acesso Negado!');window.location.href='principal.php';</script>";
    exit();
}

// INICIALIZA A VARIAVEL PARA EVITAR ERROS
$fornecedores = [];

// SE O FORMULARIO FOR ENCIADO, BUSCA O USUARIO PELO ID OU NOME

if ($_SERVER["REQUEST_METHOD"]=="POST" && !empty( $_POST["busca"] )) {
    $busca = trim($_POST["busca"]);

    // VERIFICA SE A BUSCA É UM NÚMERO OU UM NOME

    if(is_numeric($busca)) {
        $sql = "SELECT * FROM fornecedor WHERE id_fornecedor = :busca ORDER BY nome_fornecedor ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca' ,$busca,PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM fornecedor WHERE nome_fornecedor LIKE :busca_nome ORDER BY nome_fornecedor ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', "$busca%", PDO::PARAM_STR);
    }
} else {
    $sql = "SELECT * FROM fornecedor ORDER BY nome_fornecedor ASC";
    $stmt = $pdo->prepare($sql);
}

$stmt->execute();
$fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Fornecedor</title>
    <link rel="stylesheet" href="../CSS/busca.css">
</head>
<body>
<div class="container">
    <h2>Lista de Fornecedor</h2>
<!--FORMULÁRIO PARA BUSCAR USUARIOS-->
<div class="search-section">
    <form action="buscar_fornecedor.php" method="POST">
        <label for="busca">Digite o id ou NOME(opcional):</label>
        <input type="text" id="busca" name="busca" placeholder="Digite o ID ou nome do fornecedor...">
        <button type="submit">Buscar</button>
    </form>
</div>

    <?php if(!empty($fornecedores)): ?>
        <div class="table-container">
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>CNPJ</th>
                <th>Endereço</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>Contato</th>
                <th>Ações</th>
            </tr>

        <?php foreach($fornecedores as $fornecedor): ?>
            <tr>
                    <td><?= htmlspecialchars($fornecedor['id_fornecedor'])?></td>
                    <td><?= htmlspecialchars($fornecedor['nome_fornecedor'])?></td>
                    <td><?= htmlspecialchars($fornecedor['cnpj'])?></td>
                    <td><?= htmlspecialchars($fornecedor['endereco'])?></td>
                    <td><?= htmlspecialchars($fornecedor['telefone'])?></td>
                    <td><?= htmlspecialchars($fornecedor['email'])?></td>
                    <td><?= htmlspecialchars($fornecedor['contato'])?></td>
                    <td>
                    <a href="alterar_fornecedor.php?id=<?=htmlspecialchars($fornecedor['id_fornecedor'])?>" class="action-btn edit-btn ">
                    </br>
                    <a href="excluir_fornecedor.php?id=<?= htmlspecialchars($fornecedor['id_fornecedor']) ?>" class="action-btn delete-btn" onclick="return confirm('Tem certeza que deseja excluir este fornecedor?')">
            <?php endforeach; ?>
        </table>
       <?php else: ?>
            <p> Nenhum fornecedor encontrado.</p>
        <?php endif; ?>
    </div>

        <a  href="principal.php" class="back-btn">Voltar Ao Menu Principal</a>
</div>
</body>
</html>