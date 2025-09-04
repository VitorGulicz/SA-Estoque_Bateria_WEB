<?php 
require_once 'conexao.php';

if(!isset($_SESSION['usuario'])){
    header("Location:login.php");
    exit();
}

//Obtendo o nome do perfil do usuario logado
$id_perfil = $_SESSION['perfil'];
$sqlPerfil = "SELECT nome_perfil FROM perfil WHERE id_perfil = :id_perfil";
$stmtPerfil = $pdo->prepare($sqlPerfil);
$stmtPerfil->bindparam(':id_perfil',$id_perfil);
$stmtPerfil->execute();
$perfil = $stmtPerfil->fetch(PDO::FETCH_ASSOC);
$nome_perfil = $perfil['nome_perfil'];

//Definição das permissões por perfil

$permissoes = [
    1=>["Cadastrar"=>["cadastro_usuario.php","cadastro_cliente.php","cadastro_fornecedor.php","cadastro_produto.php","cadastro_funcionario.php"],
        "Buscar"=>["buscar_usuario.php","buscar_cliente.php","buscar_fornecedor.php","buscar_produto.php","buscar_funcionario.php"],
        "Alterar"=>["alterar_usuario.php","alterar_cliente.php","alterar_fornecedor.php","alterar_produto.php","alterar_funcionario.php"],
        "Excluir"=>["excluir_usuario.php","excluir_cliente.php","excluir_fornecedor.php","excluir_produto.php","excluir_funcionario.php"],
        "Compra"=>["editar_compra.php","nova_compra.php", "lista_compras.php","excluir_compra.php"],
        "Menu"=>["principal.php"],
        "Logout"=>["logout.php"]],

        2=>["Cadastrar"=>["cadastro_cliente.php"],
        "Buscar"=>["buscar_cliente.php","buscar_fornecedor.php","buscar_produto.php"],
        "Alterar"=>["alterar_cliente.php","alterar_forncedor.php"],
        "Logout"=>["logout.php"]],
    
        3=>["Cadastrar"=>["cadastro_forncedor.php","cadastro_produto.php"],
        "Buscar"=>["buscar_cliente.php","buscar_fornecedor.php","buscar_produto.php"],
        "Alterar"=>["alterar_forncedor.php","alterar_produto.php"],
        "Excluir"=>["excluir_produto.php"]],

        4=>["Buscar"=>["buscar_produto.php"],],
];

//Obtendo as opções disponiveis para o perfil logado
$opcoes_menu = $permissoes[$id_perfil]; 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/styles.css">
    <script src="../JS/scripts.js"></script>
</head>
<body>
<!-- Adicionando CSS inline com temática automotiva/bateria -->
<nav style="
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    padding: 0;
    box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
    border-bottom: 3px solid #ffc107;
    position: relative;
">
    <!-- Adicionando logo/título da loja de baterias -->
    <div style="
        background: #000;
        color: #ffc107;
        text-align: center;
        padding: 15px 0;
        font-family: 'Arial Black', Arial, sans-serif;
        font-size: 24px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 2px;
        border-bottom: 2px solid #dc3545;
        position: relative;
    ">
        ⚡ POWER BATERIAS ⚡
        <div style="
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            color: #dc3545;
        ">🔋 Energia que Move</div>
    </div>

    <ul class="menu" style="
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        background: #2d2d2d;
    ">
    <?php foreach($opcoes_menu as $categoria=>$arquivos):?>
    <li class="dropdown" style="
        position: relative;
        margin: 0 5px;
    ">
        <a href="#" style="
            display: block;
            padding: 18px 25px;
            color: #fff;
            text-decoration: none;
            font-family: Arial, sans-serif;
            font-weight: bold;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
            background: linear-gradient(45deg, #343a40, #495057);
            border: 2px solid transparent;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        " 
        onmouseover="this.style.background='linear-gradient(45deg, #ffc107, #ffca2c)'; this.style.color='#000'; this.style.borderColor='#dc3545'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 15px rgba(255,193,7,0.4)'"
        onmouseout="this.style.background='linear-gradient(45deg, #343a40, #495057)'; this.style.color='#fff'; this.style.borderColor='transparent'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            🔧 <?=$categoria ?>
        </a>
        <ul class="dropdown-menu" style="
            position: absolute;
            top: 100%;
            left: 0;
            background: #1a1a1a;
            min-width: 250px;
            list-style: none;
            padding: 10px 0;
            margin: 0;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            border: 2px solid #ffc107;
            border-radius: 8px;
            z-index: 1000;
            display: none;
        ">
            <?php foreach($arquivos as $arquivo):?>
                <li style="margin: 0;">
                <a href="<?=$arquivo ?>" style="
                    display: block;
                    padding: 12px 20px;
                    color: #fff;
                    text-decoration: none;
                    font-family: Arial, sans-serif;
                    font-size: 14px;
                    border-left: 4px solid transparent;
                    transition: all 0.3s ease;
                    position: relative;
                "
                onmouseover="this.style.background='#dc3545'; this.style.borderLeftColor='#ffc107'; this.style.paddingLeft='25px'; this.style.color='#fff'"
                onmouseout="this.style.background='transparent'; this.style.borderLeftColor='transparent'; this.style.paddingLeft='20px'; this.style.color='#fff'">
                    🔋 <?= ucfirst(str_replace("_"," ",basename($arquivo,".php")))?>
                </a>
                </li>
                <?php endforeach;?>
        </ul>
    </li>
    <?php endforeach;?>
    </ul>
</nav>

<!-- Adicionando JavaScript para controle do dropdown -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdowns = document.querySelectorAll('.dropdown');
    
    dropdowns.forEach(dropdown => {
        const menu = dropdown.querySelector('.dropdown-menu');
        
        dropdown.addEventListener('mouseenter', function() {
            menu.style.display = 'block';
            menu.style.animation = 'slideDown 0.3s ease';
        });
        
        dropdown.addEventListener('mouseleave', function() {
            menu.style.display = 'none';
        });
    });
});
</script>

<!-- Adicionando animações CSS -->
<style>
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

body {
    margin: 0;
    padding: 0;
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    min-height: 100vh;
}
</style>

</body>
</html>
