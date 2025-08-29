<?php 
session_start();
require_once 'conexao.php';
require_once 'menudrop.php';

// Verifica perfil de acesso
if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2) {
    echo "<script>alert('Acesso Negado!');window.location.href='principal.php';</script>";
    exit();
}

// Inicializa variável para evitar erros
$produtos = [];

// Se o formulário for enviado, busca o produto por ID, nome ou fornecedor
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["busca"])) {
    $busca = trim($_POST["busca"]);

    if (is_numeric($busca)) {
        $sql = "SELECT p.*, f.nome_fornecedor 
                FROM produto p
                LEFT JOIN fornecedor f ON p.id_fornecedor = f.id_fornecedor
                WHERE p.id_produto = :busca 
                ORDER BY p.tipo ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
    } else {
        $sql = "SELECT p.*, f.nome_fornecedor 
                FROM produto p
                LEFT JOIN fornecedor f ON p.id_fornecedor = f.id_fornecedor
                WHERE p.tipo LIKE :busca_nome OR f.nome_fornecedor LIKE :busca_nome
                ORDER BY p.tipo ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
    }
} else {
    // Se não houver busca, lista todos os produtos
    $sql = "SELECT p.*, f.nome_fornecedor 
            FROM produto p
            LEFT JOIN fornecedor f ON p.id_fornecedor = f.id_fornecedor
            ORDER BY p.tipo ASC";
    $stmt = $pdo->prepare($sql);
}

// Executa a consulta
try {
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<script>alert('Erro ao buscar produtos: " . addslashes($e->getMessage()) . "');</script>";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Buscar Produto</title>
<style>
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f4f8; padding: 20px; text-align: center; color: #333; }
    h2 { color: #2c3e50; margin-bottom: 20px; }
    form { margin-bottom: 30px; }
    input[type="text"] { padding: 10px; width: 250px; border: 1px solid #ccc; border-radius: 5px; outline: none; }
    input[type="text"]:focus { border-color: #2980b9; }
    button { padding: 10px 15px; border: none; background-color: #2980b9; color: white; border-radius: 5px; cursor: pointer; transition: background-color 0.2s ease; }
    button:hover { background-color: #1c5980; }
    table { margin: auto; border-collapse: separate; border-spacing: 0; width: 95%; max-width: 1000px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border-radius: 10px; overflow: hidden; }
    th, td { padding: 12px 15px; text-align: center; }
    th { background-color: #2980b9; color: white; font-weight: 600; }
    tr:nth-child(even) { background-color: #f7f9fb; }
    tr:hover { background-color: #d6eaf8; transition: 0.2s; }
    a { display: inline-block; margin: 5px 0; padding: 5px 10px; color: white; text-decoration: none; border-radius: 5px; background-color: #27ae60; transition: background-color 0.2s ease; }
    a:hover { background-color: #1e8449; }
    a.delete { background-color: #c0392b; }
    a.delete:hover { background-color: #922b21; }
    address { margin-top: 30px; font-size: 0.9em; color: #7f8c8d; }
</style>
</head>
<body>

<h2>Buscar Produtos</h2>

<form action="buscar_produto.php" method="POST">
    <label for="busca">Digite o ID, NOME do Produto ou FORNECEDOR (opcional):</label>
    <input type="text" id="busca" name="busca" placeholder="Ex: 1, Parafuso ou Max Fornecedor">
    <button type="submit">Buscar</button>
</form>

<?php if (!empty($produtos)): ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Tipo</th>
            <th>Descrição</th>
            <th>Marca</th>
            <th>Quantidade</th>
            <th>Preço</th>
            <th>Validade</th>
            <th>Fornecedor</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($produtos as $produto): ?>
            <tr>
                <td><?= htmlspecialchars($produto['id_produto']) ?></td>
                <td><?= htmlspecialchars($produto['tipo']) ?></td>
                <td><?= htmlspecialchars($produto['descricao']) ?></td>
                <td><?= htmlspecialchars($produto['marca']) ?></td>
                <td><?= htmlspecialchars($produto['qtde']) ?></td>
                <td><?= htmlspecialchars($produto['preco']) ?></td>
                <td><?= htmlspecialchars($produto['validade']) ?></td>
                <td><?= htmlspecialchars($produto['nome_fornecedor']) ?></td>
                <td>
                    <a href="alterar_produto.php?id=<?= htmlspecialchars($produto['id_produto']) ?>">Alterar</a><br>
                    <a href="excluir_produto.php?id=<?= htmlspecialchars($produto['id_produto']) ?>" class="delete" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Nenhum produto encontrado.</p>
<?php endif; ?>

<a href="principal.php">Voltar</a>

<address>
    | Max Emanoel / estudante / desenvolvimento 
</address>

</body>
</html>
