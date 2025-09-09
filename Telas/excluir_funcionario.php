<?php
session_start();
require_once 'conexao.php';
require_once 'menudrop.php';

// verifica se o usuário tem permissão para acessar a página
if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2) {
    echo "<script>alert('Acesso negado.');window.location.href='principal.php';</script>";
    exit();
}

//INICIALIZA AS VARIAVEIS
$usuario = null;

//Busca todos os fornecedor cadastrados em ordem alfabetica
$sql="SELECT * from funcionario order by nome_funcionario ASC";
$stmt = $pdo->prepare($sql);
$stmt ->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

//SE um id for passado via get, exclui o fornecedor
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    $id_funcionario = $_GET['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM funcionario WHERE id_funcionario = :id");
        $stmt->execute([':id' => $id_funcionario]);
        echo "<script>alert('Funcionario excluído com sucesso!');window.location.href='buscar_funcionario.php';</script>";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "<script>alert('Não é possível excluir este funcionario porque há compras vinculadas a ele.');window.location.href='buscar_funcionario.php';</script>";
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
    <title>🔋 Excluir Funcionário - VGM POWER</title>
    <link rel="stylesheet" href="../CSS/busca.css">
</head>
<body>
</br>
<div class="container">
<h2>Excluir Funcionário</h2>

    <?php if(!empty($usuarios)): ?>
        <div class="table-container">
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
                            <a href="excluir_funcionario.php?id=<?= $usuario['id_funcionario']; ?>" class="delete-btn" onclick="return confirm('Tem certeza que deseja excluir este funcionário?');">
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum funcionário encontrado no sistema.</p>
    <?php endif; ?>
    </div>

    <a href="principal.php" class="back-btn">Voltar ao Menu Principal</a>
    </div>
</body>
</html>
