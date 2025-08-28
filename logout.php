<?php 
session_start();
session_destroy();
header("Location:index.php");
require_once 'conexao.php';
exit();
?>