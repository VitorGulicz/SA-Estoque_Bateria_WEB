<?php
session_start();
require_once 'conexao.php';

//VERIFICA SE O USUÁRIO TEM PERMISSÃO DE ADM

if($_SESSION['perfil']!= 1){
    echo "<script>alert('Acesso Negado!');window.location.href='principal.php';</script>";
    exit();
}

if($_SERVER['REQUEST_METHOD'] =="POST"){
    $id_cliente = $_POST['id_cliente'];
    $nome_cliente = $_POST['nome_cliente'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $cpf = $_POST['cpf'];

    //Atualiza os dados do usuario
        $sql = "UPDATE cliente SET nome_cliente = :nome_cliente, endereco = :endereco, telefone = :telefone, email = :email, cpf = :cpf WHERE id_cliente = :id";
        $stmt = $pdo->prepare($sql);
    
     
    $stmt->bindparam(':nome_cliente', $nome_cliente);
    $stmt->bindparam(':endereco', $endereco);
    $stmt->bindparam(':telefone', $telefone);
    $stmt->bindparam(':email', $email);
    $stmt->bindparam(':cpf', $cpf);
    $stmt->bindparam(':id', $id_cliente);   


    if($stmt->execute()){
        echo "<script>alert('Cliente atualizado com sucesso');window.location.href='buscar_cliente.php';</script>";
    }else{
        echo "<script>alert('Erro ao atulizar o cliente');window.location.href='alterar_cliente.php?id=$cliente';</script>";
    }
}
