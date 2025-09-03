<?php
session_start();
require_once 'conexao.php';
require_once 'menudrop.php';

if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2) {
    echo "<script>alert('Acesso negado.');window.location.href='principal.php';</script>";
    exit();
}

$usuario = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['busca_funcionario'])) {
        $busca = trim($_POST['busca_funcionario']);

        if (is_numeric($busca)) {
            $sql = "SELECT * FROM funcionario WHERE id_funcionario = :busca";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':busca', $busca, PDO::PARAM_INT); 
        } else {
            $sql = "SELECT * FROM funcionario WHERE nome_funcionario LIKE :busca_nome";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR); 
        } 

        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            echo "<script>alert('Funcionario não encontrado!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Funcionario - VGM POWER</title>
    <!-- CSS externo -->
    <link rel="stylesheet" href="../CSS/cadastro.css">
    <!-- JS externos -->
    <script src="../JS/scripts.js"></script>
    <script src="../JS/mascaras.js"></script>
</head>
<body>
<h2>Alterar Funcionario</h2>

<!-- Formulário de busca -->
<form method="POST" action="alterar_funcionario.php">
    <label for="busca_funcionario">Buscar por ID ou Nome:</label>
    <input type="text" id="busca_funcionario" name="busca_funcionario" required onkeyup="BuscarSugestoes()">
    <div id="sugestoes"></div>
    <button type="submit">Buscar</button>
</form>

<?php if ($usuario): ?>
<form method="POST" action="processa_alteracao_funcionario.php">
    <input type="hidden" name="id_funcionario" value="<?=htmlspecialchars($usuario['id_funcionario']); ?>">

    <label for="nome">Nome:</label>
    <input type="text" id="nome_funcionario" name="nome_funcionario" value="<?=htmlspecialchars($usuario['nome_funcionario']); ?>" required onkeypress="mascara(this, nome)">

    <label for="cpf">CPF:</label>
    <input type="text" id="cpf" name="cpf" value="<?=htmlspecialchars($usuario['cpf']); ?>" required onkeypress="mascara(this, cpf)" maxlength="14">

    <label for="endereco">Endereço:</label>
    <input type="text" id="endereco" name="endereco" value="<?=htmlspecialchars($usuario['endereco']); ?>" required>

    <label for="telefone2">Telefone:</label>
    <input type="text" id="telefone2" name="telefone2" value="<?=htmlspecialchars($usuario['telefone']); ?>" required onkeypress="mascara(this, telefone1)" maxlength="15">

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?=htmlspecialchars($usuario['email']); ?>" required>

    <label for="dataDeContratacao">Data De Contratação:</label>
    <input type="date" id="dataDeContratacao" name="dataDeContratacao" value="<?=htmlspecialchars($usuario['dataDeContratacao']); ?>" required>

    <label for="cargo">Cargo:</label>
    <input type="text" id="cargo" name="cargo" value="<?=htmlspecialchars($usuario['cargo']); ?>" required>

    <label for="salario">Salario:</label>
    <input type="number" step="0.01" id="salario" name="salario" value="<?=htmlspecialchars($usuario['salario']); ?>" required>

    <button type="submit">Alterar</button>
    <button type="reset">Cancelar</button>
</form>
<?php endif; ?>

<a href="principal.php" class="back-btn">Voltar ao Menu Principal</a>
</body>
</html>
