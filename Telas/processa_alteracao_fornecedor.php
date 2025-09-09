<?php
session_start();
require_once 'conexao.php';

//VERIFICA SE O USUÁRIO TEM PERMISSÃO DE ADM

if($_SESSION['perfil']!= 1 && $_SESSION['perfil']!=2 && $_SESSION['perfil']!=3){
    echo "<script>alert('Acesso Negado!');window.location.href='principal.php';</script>";
    exit();
}

if($_SERVER['REQUEST_METHOD'] =="POST"){
    $id_fornecedor = $_POST['id_fornecedor'];
    $nome_fornecedor = $_POST['nome_fornecedor'];
    $cnpj = $_POST['cnpj'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $contato = $_POST['contato'];

    //Atualiza os dados do usuario
        $sql = "UPDATE fornecedor SET nome_fornecedor = :nome_fornecedor, cnpj = :cnpj, endereco = :endereco, telefone = :telefone, email = :email, contato = :contato WHERE id_fornecedor = :id";
        $stmt = $pdo->prepare($sql);
    
     
    $stmt->bindparam(':nome_fornecedor', $nome_fornecedor);
    $stmt->bindparam(':cnpj', $cnpj);
    $stmt->bindparam(':endereco', $endereco);
    $stmt->bindparam(':telefone', $telefone);
    $stmt->bindparam(':email', $email);
    $stmt->bindparam(':contato', $contato);
    $stmt->bindparam(':id', $id_fornecedor);   


    if($stmt->execute()){
        echo "<script>alert('Fornecedor atualizado com sucesso');window.location.href='buscar_fornecedor.php';</script>";
    }else{
        echo "<script>alert('Erro ao atulizar o Fornecedor');window.location.href='alterar_fornecedor.php?id=$fornecedor';</script>";
    }
}
