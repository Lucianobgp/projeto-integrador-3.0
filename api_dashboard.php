<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

// Mês e ano selecionados, default para mês e ano atuais
$mes = isset($_GET['mes']) ? intval($_GET['mes']) : date('m');
$ano = isset($_GET['ano']) ? intval($_GET['ano']) : date('Y');

try {
    // Conexão com o banco
    $pdo = new PDO(
        "mysql:host=db_financaspi.mysql.dbaas.com.br;dbname=db_financaspi;charset=utf8",
        "db_financaspi",
        "Gzfinanceiro1@",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Buscar anos disponíveis
    $anos = $pdo->query("SELECT DISTINCT YEAR(data_venc) AS ano FROM tb_lancamento ORDER BY ano DESC")
                ->fetchAll(PDO::FETCH_COLUMN);

    // Buscar meses disponíveis no ano selecionado
    $stmt = $pdo->prepare("SELECT DISTINCT MONTH(data_venc) AS mes FROM tb_lancamento WHERE YEAR(data_venc) = :ano ORDER BY mes ASC");
    $stmt->execute(['ano' => $ano]);
    $mesesDisponiveis = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Receita
    $stmt = $pdo->prepare("
        SELECT total_recebimento 
        FROM view_recebimento_soma_por_mes_ano 
        WHERE mes = :mes AND ano = :ano
    ");
    $stmt->execute(['mes'=>$mes, 'ano'=>$ano]);
    $receita = $stmt->fetch(PDO::FETCH_ASSOC)['total_recebimento'] ?? 0;

    // Despesa
    $stmt = $pdo->prepare("
        SELECT total_pagamento 
        FROM view_pagamento_soma_por_mes_ano 
        WHERE mes = :mes AND ano = :ano
    ");
    $stmt->execute(['mes'=>$mes, 'ano'=>$ano]);
    $despesa = $stmt->fetch(PDO::FETCH_ASSOC)['total_pagamento'] ?? 0;

    // Saldo
    $saldo = $receita - $despesa;

    echo json_encode([
        'anos' => $anos,
        'mesesDisponiveis' => $mesesDisponiveis,
        'receita' => floatval($receita),
        'despesa' => floatval($despesa),
        'saldo' => floatval($saldo)
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'erro' => 'Erro ao acessar o banco de dados',
        'detalhe' => $e->getMessage()
    ]);
}
