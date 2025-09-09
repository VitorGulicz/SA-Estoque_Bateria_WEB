<?php
session_start();
require_once 'conexao.php';

// VERIFICA SE O USUARIO TEM PERMISSAO DE ADM
if ($_SESSION['perfil'] != 1 && $_SESSION['perfil']!=2){
    echo"<script>alert('Acesso Negado');window.location.href='principal.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] =="POST"){
    $id_funcionario   = $_POST["id_funcionario"];
    $nome_funcionario = $_POST["nome_funcionario"];
    $cpf         = $_POST["cpf"];
    $endereco         = $_POST["endereco"];
    $telefone         = $_POST["telefone2"];
    $email            = $_POST["email"];
    $data           = $_POST["dataDeContratacao"];
    $cargo           = $_POST["cargo"];
    $salario        = $_POST["salario"];

    // ATUALIZA OS DADOS DO FUNCIONÁRIO
    $sql = "UPDATE funcionario 
            SET nome_funcionario = :nome_funcionario, 
                cpf = :cpf, 
                endereco = :endereco, 
                telefone = :telefone, 
                email = :email,
                dataDeContratacao = :dataDeContratacao,
                cargo = :cargo,
                salario = :salario
            WHERE id_funcionario = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome_funcionario',$nome_funcionario);
    $stmt->bindParam(':cpf',$cpf);
    $stmt->bindParam(':endereco',$endereco);
    $stmt->bindParam(':telefone',$telefone);
    $stmt->bindParam(':email',$email);
    $stmt->bindParam(':id',$id_funcionario);
    $stmt->bindParam(':dataDeContratacao',$data);
    $stmt->bindParam(':cargo',$cargo);
    $stmt->bindParam(':salario',$salario);

    if($stmt->execute()) {
        echo"<script>alert('Funcionário atualizado com sucesso!');window.location.href='buscar_funcionario.php';</script>";
    } else {
        echo"<script>alert('Erro ao atualizar o funcionário!');window.location.href='alterar_funcionario.php?id=$id_funcionario';</script>";
    }
}
?>
