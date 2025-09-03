<?php
session_start();
require_once 'conexao.php';

// Verifica permissão
if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso Negado!');window.location.href='principal.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Captura os dados do formulário
    $id_produto = (int)$_POST['id_produto'];
    $id_fornecedor = (int)$_POST['fornecedor']; // ID do fornecedor
    $tipo = trim($_POST['tipo']);
    $voltagem = trim($_POST['voltagem']);
    $descricao = trim($_POST['descricao']);
    $marca = trim($_POST['marca']);
    $qtde = (int)$_POST['qtde'];
    $preco = str_replace(',', '.', str_replace('.', '', $_POST['preco'])); // Converte preço para formato decimal
    $validade = trim($_POST['validade']);

    try {
        // === VALIDAÇÃO: Verifica se o fornecedor existe ===
        $stmtCheck = $pdo->prepare("SELECT id_fornecedor FROM fornecedor WHERE id_fornecedor = ?");
        $stmtCheck->execute([$id_fornecedor]);
        if (!$stmtCheck->fetch()) {
            throw new Exception("Fornecedor selecionado é inválido ou não existe.");
        }

        // === VALIDAÇÃO: Verifica se o produto existe ===
        $stmtProduto = $pdo->prepare("SELECT id_produto FROM produto WHERE id_produto = ?");
        $stmtProduto->execute([$id_produto]);
        if (!$stmtProduto->fetch()) {
            throw new Exception("Produto não encontrado.");
        }

        // === VALIDAÇÃO: Verifica se as colunas necessárias existem na tabela ===
        $requiredColumns = ['id_fornecedor', 'tipo', 'voltagem', 'descricao', 'marca', 'qtde', 'preco', 'validade'];
        $stmtColumns = $pdo->query("DESCRIBE produto")->fetchAll(PDO::FETCH_COLUMN);
        foreach ($requiredColumns as $col) {
            if (!in_array($col, $stmtColumns)) {
                throw new Exception("Coluna '$col' não encontrada na tabela produto.");
            }
        }

        // === ATUALIZAÇÃO DO PRODUTO ===
        $sql = "UPDATE produto 
                SET id_fornecedor = :id_fornecedor, 
                    tipo = :tipo, 
                    voltagem = :voltagem,
                    descricao = :descricao, 
                    marca = :marca, 
                    qtde = :qtde,  
                    preco = :preco,
                    validade = :validade
                WHERE id_produto = :id_produto";

        $stmt = $pdo->prepare($sql);

        // Bind dos parâmetros
        $stmt->bindParam(':id_fornecedor', $id_fornecedor, PDO::PARAM_INT);
        $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
        $stmt->bindParam(':voltagem', $voltagem, PDO::PARAM_STR);
        $stmt->bindParam(':descricao', $descricao, PDO::PARAM_STR);
        $stmt->bindParam(':marca', $marca, PDO::PARAM_STR);
        $stmt->bindParam(':qtde', $qtde, PDO::PARAM_INT);
        $stmt->bindParam(':preco', $preco, PDO::PARAM_STR); // ou PDO::PARAM_DECIMAL, mas STRING funciona com bind
        $stmt->bindParam(':validade', $validade, PDO::PARAM_STR);
        $stmt->bindParam(':id_produto', $id_produto, PDO::PARAM_INT);

        // Executa a atualização
        if ($stmt->execute()) {
            echo "<script>alert('Produto atualizado com sucesso!');window.location.href='buscar_produto.php';</script>";
        } else {
            $errorInfo = $stmt->errorInfo();
            throw new Exception("Erro ao atualizar: " . $errorInfo[2]);
        }

    } catch (Exception $e) {
        // Escapa caracteres especiais para não quebrar o JavaScript
        $errorMessage = addslashes($e->getMessage());
        echo "<script>alert('Erro: $errorMessage');window.location.href='alterar_produto.php';</script>";
    }
}
?>