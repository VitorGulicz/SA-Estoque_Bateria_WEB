<?php
session_start();
require_once 'conexao.php';

//VERIFICA SE O USUÁRIO TEM PERMISSÃO DE ADM

if($_SESSION['perfil']!= 1){
    echo "<script>alert('Acesso Negado!');window.location.href='principal.php';</script>";
    exit();
}

if($_SERVER['REQUEST_METHOD'] =="POST"){
    $id_usuario = $_POST['id_usuario'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $id_perfil = $_POST['id_perfil'];
    $nova_senha = !empty($_POST['nova_senha']) ? password_hash($_POST['nova_senha'], PASSWORD_DEFAULT): null;

    //Atualiza os dados do usuario
    if($nova_senha){
        $sql = "UPDATE usuario SET nome = :nome, email =:email, id_perfil = :id_perfil, senha = :senha WHERE id_usuario = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindparam(':senha',$nova_senha);
    }else{
        $sql = "UPDATE usuario SET nome = :nome, email =:email, id_perfil = :id_perfil WHERE id_usuario = :id";
        $stmt = $pdo->prepare($sql);
    }
    $stmt->bindparam(':nome',$nome);
    $stmt->bindparam(':email',$email);
    $stmt->bindparam(':id_perfil',$id_perfil);
    $stmt->bindparam(':id',$id_usuario);

    if($stmt->execute()){
        echo "<script>alert('Usuario atualizado com sucesso');window.location.href='buscar_usuario.php';</script>";
    }else{
        echo "<script>alert('Erro ao atulizar o usuario');window.location.href='alterar_usuario.php?id=$usuario';</script>";
    }
}