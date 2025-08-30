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
$fornecedor = null;

//Busca todos os fornecedor cadastrados em ordem alfabetica
$sql="SELECT * from fornecedor order by nome_fornecedor ASC";
$stmt = $pdo->prepare($sql);
$stmt ->execute();
$fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

//SE um id for passado via get, exclui o fornecedor
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    $id_fornecedor = $_GET['id'];

    //Exclui o fornecedor do banco de dados
    $sql = "DELETE FROM fornecedor WHERE id_fornecedor = :id";
    $stmt=$pdo->prepare($sql);
    $stmt->bindparam(':id',$id_fornecedor,PDO::PARAM_INT);

    if($stmt->execute()){
        echo "<script>alert('Fornecedor excluido com sucesso!');window.location.href='excluir_fornecedor.php';</script>";
    }else{
        echo "<script>alert('Erro ao excluir fornecedor!');</script>";
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
    <?php if (!empty($fornecedores)):?>
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
                        <a href="excluir_fornecedor.php?id=<?= htmlspecialchars($fornecedor['id_fornecedor'])?>"onclick="return confirm('Tem certeza que deseja excluir este fornecedor')" class="excluir">
                            Excluir</a>
                    </td>
                </tr>
                <?php endforeach;?>
        </table>
        <?php else: ?>
            <p>Nenhum fornecedor encontrado</p>
        <?php endif;?>
        <a href="principal.php" class="voltar">Voltar</a>
</body>
</html>