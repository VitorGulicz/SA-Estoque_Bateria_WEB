<?php
session_start();
require_once 'conexao.php';
require_once 'menudrop.php';

if($_SESSION['perfil']!=1 && $_SESSION['perfil']!=2) {
    echo "<script>alert('Acesso Negado!');window.location.href='principal.php';</script>";
    exit();
}

// INICIALIZA A VARIAVEL PARA EVITAR ERROS
$clientees = [];

// SE O FORMULARIO FOR ENCIADO, BUSCA O USUARIO PELO ID OU NOME

if ($_SERVER["REQUEST_METHOD"]=="POST" && !empty( $_POST["busca"] )) {
    $busca = trim($_POST["busca"]);

    // VERIFICA SE A BUSCA É UM NÚMERO OU UM NOME

    if(is_numeric($busca)) {
        $sql = "SELECT * FROM cliente WHERE id_cliente = :busca ORDER BY nome_cliente ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca' ,$busca,PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM cliente WHERE nome_cliente LIKE :busca_nome ORDER BY nome_cliente ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', "$busca%", PDO::PARAM_STR);
    }
} else {
    $sql = "SELECT * FROM cliente ORDER BY nome_cliente ASC";
    $stmt = $pdo->prepare($sql);
}

$stmt->execute();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Cliente</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <link rel="stylesheet" href="../CSS/tabela.css">
    <link rel="stylesheet" href="../CSS/busca.css">
</head>
<body>
<div class="container">
    <h2>Lista de Clientes</h2>
<!--FORMULÁRIO PARA BUSCAR USUARIOS-->
<div class="search-section">
    <form action="buscar_cliente.php" method="POST">
        <label for="busca">Digite o id ou NOME(opcional):</label>
        <input type="text" id="busca" name="busca" placeholder="Digite o ID ou nome do cliente...">
        <button type="submit">Buscar</button>
    </form>
</div>

    <?php if(!empty($clientes)): ?>
        <div class="table-container">
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Endereço</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>CPF</th>
                <th>Ações</th>
            </tr>

        <?php foreach($clientes as $cliente): ?>
            <tr>
                    <td><?= htmlspecialchars($cliente['id_cliente'])?></td>
                    <td><?= htmlspecialchars($cliente['nome_cliente'])?></td>
                    <td><?= htmlspecialchars($cliente['endereco'])?></td>
                    <td><?= htmlspecialchars($cliente['telefone'])?></td>
                    <td><?= htmlspecialchars($cliente['email'])?></td>
                    <td><?= htmlspecialchars($cliente['cpf'])?></td>
                    <td>
                    <a href="alterar_cliente.php?id=<?=htmlspecialchars($cliente['id_cliente'])?>" class="action-btn edit-btn ">
                    </br>
                    <a href="excluir_cliente.php?id=<?= htmlspecialchars($cliente['id_cliente']) ?>" class="action-btn delete-btn" onclick="return confirm('Tem certeza que deseja excluir este cliente?')">
            <?php endforeach; ?>
        </table>
       <?php else: ?>
            <p> Nenhum cliente encontrado.</p>
        <?php endif; ?>
    </div>

        <a  href="principal.php" class="back-btn">Voltar Ao Menu Principal</a>
</div>
</body>
</html>