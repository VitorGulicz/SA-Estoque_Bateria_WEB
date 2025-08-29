<?php
session_start();
require_once 'conexao.php';
require_once 'menudrop.php';

//VERIFICA SE O USUÁRIO TEM PERMISSÃO DE ADM

if($_SESSION['perfil']!= 1){
    echo "<script>alert('Acesso Negado!');window.location.href='principal.php';</script>";
    exit();
}

//INICIALIZA AS VARIAVEIS
$cliente = null;

//Busca todos os cliente cadastrados em ordem alfabetica
$sql="SELECT * from cliente order by nome_cliente ASC";
$stmt = $pdo->prepare($sql);
$stmt ->execute();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

//SE um id for passado via get, exclui o cliente
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    $id_cliente = $_GET['id'];

    //Exclui o cliente do banco de dados
    $sql = "DELETE FROM cliente WHERE id_cliente = :id";
    $stmt=$pdo->prepare($sql);
    $stmt->bindparam(':id',$id_cliente,PDO::PARAM_INT);

    if($stmt->execute()){
        echo "<script>alert('cliente excluido com sucesso!');window.location.href='excluir_cliente.php';</script>";
    }else{
        echo "<script>alert('Erro ao excluir cliente!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Usuario</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <link rel="stylesheet" href="../CSS/tabela.css">
</head>
<body>
    <h2>Excluir Usuario</h2>
    <?php if (!empty($clientes)):?>
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
                        <a href="excluir_cliente.php?id=<?= htmlspecialchars($cliente['id_cliente'])?>"onclick="return confirm('Tem certeza que deseja excluir este cliente')" class="excluir">
                            Excluir</a>
                    </td>
                </tr>
                <?php endforeach;?>
        </table>
        <?php else: ?>
            <p>Nenhum cliente encontrado</p>
        <?php endif;?>
        <a href="principal.php" class="voltar">Voltar</a>
</body>
</html>