<?php
session_start();
require_once 'conexao.php';

if($_SESSION['perfil'] != 1){
    echo "<script>alert('Acesso Negado!');window.location.href='principal.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_produto = (int)$_POST['id_produto'];
    $fornecedor = trim($_POST['fornecedor']);
    $tipo = trim($_POST['tipo']);
    $voltagem = trim($_POST['voltagem']);
    $descricao = trim($_POST['descricao']);
    $marca = trim($_POST['marca']);
    $qtde = (int)$_POST['qtde'];
    $preco = str_replace(',', '.', str_replace('.', '', $_POST['preco']));
    $validade = trim($_POST['validade']);

    $sql = "UPDATE produto 
            SET fornecedor = :fornecedor, 
                tipo = :tipo, 
                voltagem = :voltagem,
                descricao = :descricao, 
                marca = :marca, 
                qtde = :qtde,  
                preco = :preco,
                validade = :validade
            WHERE id_produto = :id_produto";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':fornecedor', $fornecedor);
    $stmt->bindParam(':tipo', $tipo);
    $stmt->bindParam(':voltagem', $voltagem);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':marca', $marca);
    $stmt->bindParam(':qtde', $qtde);
    $stmt->bindParam(':preco', $preco);
    $stmt->bindParam(':validade', $validade);
    $stmt->bindParam(':id_produto', $id_produto);

    if($stmt->execute()){
        echo "<script>alert('Produto atualizado com sucesso!');window.location.href='alterar_produto.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar o produto!');window.location.href='alterar_produto.php';</script>";
    }
}
?>
