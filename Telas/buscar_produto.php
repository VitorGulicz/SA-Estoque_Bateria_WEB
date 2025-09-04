<?php 
session_start();
require_once 'conexao.php';
require_once 'menudrop.php';

// Verifica perfil de acesso
if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2 && $_SESSION['perfil']!=3 && $_SESSION['perfil']!=4) {
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
<link rel="stylesheet" href="../CSS/busca.css">
</head>
<body>
<div class="container">
<h2>Buscar Produtos</h2>

<div class="search-section">
<form action="buscar_produto.php" method="POST">
    <label for="busca">Digite o ID, NOME do Produto ou FORNECEDOR (opcional):</label>
    <input type="text" id="busca" name="busca" placeholder="Digite o ID ou nome do produto...">
    <button type="submit">Buscar</button>
</form>
</div>

<?php if (!empty($produtos)): ?>
    <div class="table-container">
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
                    <a href="alterar_produto.php?id=<?= htmlspecialchars($produto['id_produto']) ?>" class="action-btn edit-btn ">
                    <a href="excluir_produto.php?id=<?= htmlspecialchars($produto['id_produto']) ?>" class="action-btn delete-btn" onclick="return confirm('Tem certeza que deseja excluir este produto?')">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Nenhum produto encontrado.</p>
<?php endif; ?>
</div>

<a href="principal.php" class="back-btn">Voltar Ao Menu Principal</a>
</div>
<address>
</address>

</body>
</html>
