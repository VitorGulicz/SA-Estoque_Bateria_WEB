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
    <script src="JS\scripts.js"></script>
</head>
<body style="
    margin: 0;
    padding: 0;
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    min-height: 100vh;
    font-family: Arial, sans-serif;
">
    <!-- Redesenhando o header com temática automotiva -->
    <header style="
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        padding: 30px 0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        border-bottom: 4px solid #ffc107;
        position: relative;
        overflow: hidden;
    ">
        <!-- Adicionando padrão de fundo automotivo -->
        <div style="
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(220, 53, 69, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 193, 7, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(220, 53, 69, 0.1) 0%, transparent 50%);
            z-index: 1;
        "></div>

        <div style="
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            position: relative;
            z-index: 2;
        ">
            <div class="saudacao" style="
                color: #fff;
                flex: 1;
            ">
                <!-- Melhorando a saudação com ícones automotivos -->
                <div style="
                    background: linear-gradient(45deg, #dc3545, #c82333);
                    padding: 20px 30px;
                    border-radius: 15px;
                    border: 3px solid #ffc107;
                    box-shadow: 0 8px 25px rgba(220, 53, 69, 0.3);
                    position: relative;
                    overflow: hidden;
                ">
                    <div style="
                        position: absolute;
                        top: -50%;
                        right: -20px;
                        font-size: 120px;
                        color: rgba(255, 193, 7, 0.1);
                        transform: rotate(15deg);
                        z-index: 1;
                    ">🔋</div>
                    
                    <h2 style="
                        margin: 0;
                        font-size: 28px;
                        font-weight: bold;
                        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
                        position: relative;
                        z-index: 2;
                        color: #fff;
                    ">
                        🚗 Bem-vindo, <?php echo $_SESSION['usuario'];?>!
                    </h2>
                    
                    <div style="
                        margin-top: 10px;
                        padding: 8px 15px;
                        background: rgba(0,0,0,0.3);
                        border-radius: 20px;
                        display: inline-block;
                        position: relative;
                        z-index: 2;
                    ">
                        <span style="
                            color: #ffc107;
                            font-weight: bold;
                            font-size: 16px;
                        ">⚡ Perfil: <?php echo $nome_perfil;?></span>
                    </div>
                </div>
            </div> 

            <div class="logout" style="
                margin-left: 30px;
            ">
                <!-- Estilizando o botão de logout com tema automotivo -->
                <form action="logout.php" method="POST">
                    <button type="submit" style="
                        background: linear-gradient(45deg, #6c757d, #5a6268);
                        color: #fff;
                        border: 3px solid #ffc107;
                        padding: 15px 25px;
                        font-size: 16px;
                        font-weight: bold;
                        border-radius: 10px;
                        cursor: pointer;
                        transition: all 0.3s ease;
                        text-transform: uppercase;
                        letter-spacing: 1px;
                        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                        position: relative;
                        overflow: hidden;
                    "
                    onmouseover="this.style.background='linear-gradient(45deg, #dc3545, #c82333)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(220,53,69,0.4)'"
                    onmouseout="this.style.background='linear-gradient(45deg, #6c757d, #5a6268)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.2)'">
                        🔌 Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Adicionando seção de informações da loja -->
    <main style="
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    ">
        <div style="
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            border: 3px solid #ffc107;
            text-align: center;
            position: relative;
            overflow: hidden;
        ">
            <div style="
                position: absolute;
                top: -50px;
                left: -50px;
                font-size: 200px;
                color: rgba(255, 193, 7, 0.05);
                z-index: 1;
            ">🔋</div>
            
            <h1 style="
                color: #ffc107;
                font-size: 36px;
                margin: 0 0 20px 0;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
                position: relative;
                z-index: 2;
            ">⚡ POWER BATERIAS ⚡</h1>
            
            <p style="
                color: #fff;
                font-size: 18px;
                margin: 0 0 30px 0;
                position: relative;
                z-index: 2;
            ">Sua energia automotiva em boas mãos!</p>
            
            <div style="
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 20px;
                margin-top: 30px;
                position: relative;
                z-index: 2;
            ">
                <div style="
                    background: #dc3545;
                    padding: 20px;
                    border-radius: 10px;
                    color: #fff;
                    text-align: center;
                ">
                    <div style="font-size: 40px; margin-bottom: 10px;">🚗</div>
                    <h3 style="margin: 0 0 10px 0;">Baterias Automotivas</h3>
                    <p style="margin: 0; font-size: 14px;">Para carros, motos e caminhões</p>
                </div>
                
                <div style="
                    background: #ffc107;
                    padding: 20px;
                    border-radius: 10px;
                    color: #000;
                    text-align: center;
                ">
                    <div style="font-size: 40px; margin-bottom: 10px;">⚡</div>
                    <h3 style="margin: 0 0 10px 0;">Instalação Rápida</h3>
                    <p style="margin: 0; font-size: 14px;">Serviço especializado</p>
                </div>
                
                <div style="
                    background: #28a745;
                    padding: 20px;
                    border-radius: 10px;
                    color: #fff;
                    text-align: center;
                ">
                    <div style="font-size: 40px; margin-bottom: 10px;">🔧</div>
                    <h3 style="margin: 0 0 10px 0;">Manutenção</h3>
                    <p style="margin: 0; font-size: 14px;">Teste e revisão completa</p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
