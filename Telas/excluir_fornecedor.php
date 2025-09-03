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

    try {
        $stmt = $pdo->prepare("DELETE FROM fornecedor WHERE id_fornecedor = :id");
        $stmt->execute([':id' => $id_fornecedor]);
        echo "<script>alert('Fornecedor excluído com sucesso!');window.location.href='buscar_fornecedor.php';</script>";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "<script>alert('Não é possível excluir este fornecedor porque há compras vinculadas a ele.');window.location.href='buscar_fornecedor.php';</script>";
        } else {
            echo "Erro: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Usuario</title>
    <link rel="stylesheet" href="../CSS/busca.css">
</head>
<body>
</br>
<div class="container">
    <h2>Excluir Usuario</h2>
    <?php if (!empty($fornecedores)):?>
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
                        <a href="excluir_fornecedor.php?id=<?= htmlspecialchars($fornecedor['id_fornecedor'])?>" class="action-btn delete-btn" onclick="return confirm('Tem certeza que deseja excluir este fornecedor')">
                    </td>
                </tr>
                <?php endforeach;?>
        </table>
        <?php else: ?>
            <p>Nenhum fornecedor encontrado</p>
        <?php endif;?>
    </div>
        <a href="principal.php" class="back-btn">Voltar ao Menu Principal</a>
    </div>
</body>
</html>