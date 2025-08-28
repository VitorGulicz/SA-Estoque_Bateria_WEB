<?php 
session_start();
require_once 'menudrop.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Principal</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <script src="JS\scripts.js"></script>
</head>
<body>
    <header>
    <div class="saudacao">
        <h2>Bem vindo, <?php echo $_SESSION['usuario'];?>! Perfil: <?php echo $nome_perfil;?><h2>
    </div> 

    <div class="logout">
        <form action="logout.php" method="POST">
            <button type="submit">Logout</button>
        </form>
    </div>
</header>
</body>
</html>