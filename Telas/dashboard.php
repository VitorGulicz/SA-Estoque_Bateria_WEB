<?php
require_once "conexao.php";

// KPIs
$clientes     = $pdo->query("SELECT COUNT(*) AS total FROM cliente")->fetch(PDO::FETCH_ASSOC)['total'];
$fornecedores = $pdo->query("SELECT COUNT(*) AS total FROM fornecedor")->fetch(PDO::FETCH_ASSOC)['total'];
$funcionarios = $pdo->query("SELECT COUNT(*) AS total FROM funcionario")->fetch(PDO::FETCH_ASSOC)['total'];
$produtos     = $pdo->query("SELECT COUNT(*) AS total FROM produto")->fetch(PDO::FETCH_ASSOC)['total'];
$estoque      = $pdo->query("SELECT SUM(qtde) AS total FROM produto")->fetch(PDO::FETCH_ASSOC)['total'];

// Estoque por marca
$estoquePorMarca = [];
$res = $pdo->query("SELECT marca, SUM(qtde) AS qtde FROM produto GROUP BY marca");
while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
    $estoquePorMarca[] = $row;
}
// Compras por cliente
// Compras por funcionário
$comprasFuncionarios = [];
$res = $pdo->query("
    SELECT f.nome_funcionario, COALESCE(SUM(co.quantidade),0) AS total_itens
    FROM funcionario f
    LEFT JOIN compra co ON co.cod_funcionario = f.id_funcionario
    GROUP BY f.nome_funcionario
");
while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
    $comprasFuncionarios[] = $row;
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Dashboard VGM Power</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="../CSS/cadastro.css">
  <style>
    body { font-family: Arial, sans-serif; background: #111; color: #eee; margin: 20px; }
    .cards { display: flex; gap: 20px; flex-wrap: wrap; }
    .card { flex: 1; min-width: 150px; background: #222; padding: 20px; border-radius: 10px; text-align: center; }
    .charts { display: flex; gap: 20px; flex-wrap: wrap; margin-top: 30px; }
    .chart { flex: 1; min-width: 300px; background: #222; padding: 20px; border-radius: 10px; }
  </style>
</head>
<body>
  <h1>📊 Dashboard - Loja de Baterias</h1>
  
  

  <!-- KPIs -->
  <div class="cards">
    <div class="card"><h2><?= $clientes ?></h2><p>Clientes</p></div>
    <div class="card"><h2><?= $fornecedores ?></h2><p>Fornecedores</p></div>
    <div class="card"><h2><?= $funcionarios ?></h2><p>Funcionários</p></div>
    <div class="card"><h2><?= $produtos ?></h2><p>Produtos</p></div>
    <div class="card"><h2><?= $estoque ?></h2><p>Estoque Total</p></div>
  </div>

  <!-- Gráficos -->
  <div class="charts">
    <div class="chart">
      <h3>Estoque por Marca</h3>
      <canvas id="estoqueMarca"></canvas>
    </div> 
    <div class="chart">
      <h3>Compras por Funcionário</h3>
      <canvas id="comprasFuncionarios"></canvas>
    </div>
  </div>

  </div>

  <script>
    // Estoque por Marca
    const ctx1 = document.getElementById('estoqueMarca');
    new Chart(ctx1, {
      type: 'bar',
      data: {
        labels: <?= json_encode(array_column($estoquePorMarca, 'marca')) ?>,
        datasets: [{
          label: 'Quantidade no Estoque',
          data: <?= json_encode(array_map('intval', array_column($estoquePorMarca, 'qtde'))) ?>,
          backgroundColor: 'rgba(54, 162, 235, 0.7)'
        }]
      }
    });

// Compras por Funcionário (pizza menor)
const ctx2 = document.getElementById('comprasFuncionarios');
new Chart(ctx2, {
  type: 'pie',
  data: {
    labels: <?= json_encode(array_column($comprasFuncionarios, 'nome_funcionario')) ?>,
    datasets: [{
      label: 'Compras',
      data: <?= json_encode(array_map('intval', array_column($comprasFuncionarios, 'total_itens'))) ?>,
      backgroundColor: [
        'rgba(255, 99, 132, 0.7)',
        'rgba(54, 162, 235, 0.7)',
        'rgba(255, 206, 86, 0.7)',
        'rgba(75, 192, 192, 0.7)',
        'rgba(153, 102, 255, 0.7)'
      ]
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: true
  }
});

    
  </script>
</body>
<a href="principal.php" class="back-btn">Voltar ao Menu Principal</a>
</html>