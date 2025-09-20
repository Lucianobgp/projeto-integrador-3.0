<?php
header("Access-Control-Allow-Origin: http://gzfinanceiropessoal.com.br");
header('Content-Type: application/json; charset=utf-8');

$host = "db_financaspi.mysql.dbaas.com.br";
$db   = "db_financaspi";
$user = "db_financaspi";
$pass = "Gzfinanceiro1@";
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    $tipos = array_map(fn($t) => ['id' => $t['id_cad_tipo'], 'nome' => $t['desc_tipo']],
                        $pdo->query("SELECT id_cad_tipo, desc_tipo FROM tb_cad_tipo ORDER BY desc_tipo ASC")->fetchAll());
    
    $planos = array_map(fn($p) => ['id' => $p['id_cad_plano'], 'nome' => $p['desc_plano']],
                         $pdo->query("SELECT id_cad_plano, desc_plano FROM tb_cad_plano ORDER BY desc_plano ASC")->fetchAll());
    
    $formas = array_map(fn($f) => ['id' => $f['id_cad_forma'], 'nome' => $f['desc_forma']],
                         $pdo->query("SELECT id_cad_forma, desc_forma FROM tb_cad_forma ORDER BY desc_forma ASC")->fetchAll());
    
    $bancos = array_map(fn($b) => ['id' => $b['id_cad_banco'], 'nome' => $b['nome_banco']],
                         $pdo->query("SELECT id_cad_banco, nome_banco FROM tb_cad_banco ORDER BY nome_banco ASC")->fetchAll());
    
    $cartoes = array_map(fn($c) => ['id' => $c['id_cad_cartao'], 'nome' => $c['nome_cartao']],
                          $pdo->query("SELECT id_cad_cartao, nome_cartao FROM tb_cad_cartao ORDER BY nome_cartao ASC")->fetchAll());

    echo json_encode([
        'tipos' => $tipos,
        'planos' => $planos,
        'formas' => $formas,
        'bancos' => $bancos,
        'cartoes' => $cartoes
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao conectar ao banco: ' . $e->getMessage()]);
}
