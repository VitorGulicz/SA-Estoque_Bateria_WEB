<?php 
session_start();
require_once 'menudrop2.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Power Baterias - Sistema Principal</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <script src="JS/scripts.js"></script>
    <link rel="stylesheet" href="../CSS/principal.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-bg"></div>

        <div class="header-container">
            <div class="saudacao">
                <div class="saudacao-box">
                    <div class="icon-bg">🔋</div>
                    
                    <h2>
                        🚗 Bem-vindo, <?php echo $_SESSION['usuario'];?>!
                    </h2>
                    
                    <div class="perfil">
                        <span>⚡ Perfil: <?php echo $nome_perfil;?></span>
                    </div>
                </div>
            </div> 

            <div class="logout">
                <form action="logout.php" method="POST">
                    <button type="submit" class="btn-logout">
                        🔌 Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main -->
    <main>
        <div class="main-box">
            <div class="main-icon">🔋</div>
            
            <h1>⚡ POWER BATERIAS ⚡</h1>
            <p>Sua energia automotiva em boas mãos!</p>
            
            <div class="grid-cards">
                <div class="card red">
                    <div class="emoji">🚗</div>
                    <h3>Baterias Automotivas</h3>
                    <p>Para carros, motos e caminhões</p>
                </div>
                
                <div class="card yellow">
                    <div class="emoji">⚡</div>
                    <h3>Instalação Rápida</h3>
                    <p>Serviço especializado</p>
                </div>
                
                <div class="card green">
                    <div class="emoji">🔧</div>
                    <h3>Manutenção</h3>
                    <p>Teste e revisão completa</p>
                </div>
            </div>
</br>
</br>
</br>
                <div class="dashboard">
                    <a href="dashboard.php" class="btn-logout">
                        Dashboard 📊
                    </a>
                </div>  
         </div>
        </div>
    </main>
</body>
</html>
