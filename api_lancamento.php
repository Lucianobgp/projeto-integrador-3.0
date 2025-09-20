<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: http://gzfinanceiropessoal.com.br");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

// Se for preflight do CORS, apenas responde e encerra
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Configuração do banco
$dsn = "mysql:host=db_financaspi.mysql.dbaas.com.br;dbname=db_financaspi;charset=utf8";
$user = "db_financaspi"; // ajuste se necessário
$pass = "Gzfinanceiro1@";     // ajuste se necessário

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["message" => "Erro na conexão: " . $e->getMessage()]);
    exit;
}

// Lê os dados do corpo da requisição
$inputJSON = file_get_contents("php://input");
$data = json_decode($inputJSON, true);

// Debug opcional (cria um arquivo para inspecionar os dados recebidos)
// file_put_contents("debug_lancamento.txt", $inputJSON);

// Verifica se recebeu dados
if (!$data) {
    http_response_code(400);
    echo json_encode(["message" => "Nenhum dado recebido."]);
    exit;
}

// Validação — não usamos empty() para não rejeitar valores 0
$camposObrigatorios = ['id_cad_tipo','id_cad_plano','desc_lanc','data_venc','valor_lanc','id_cad_forma','id_cad_banco','id_cad_cartao'];
foreach ($camposObrigatorios as $campo) {
    if (!isset($data[$campo]) || ($campo === 'desc_lanc' && trim($data[$campo]) === '')) {
        http_response_code(400);
        echo json_encode(["message" => "Dados inválidos. Campo faltando: $campo"]);
        exit;
    }
}

// Prepara os dados para inserir
$id_cad_tipo   = (int) $data['id_cad_tipo'];
$id_cad_plano  = (int) $data['id_cad_plano'];
$desc_lanc     = trim($data['desc_lanc']);
$data_venc     = $data['data_venc']; // já vem no formato YYYY-MM-DD
$valor_lanc    = number_format((float) str_replace(',', '.', $data['valor_lanc']), 2, '.', ''); // Formato decimal
$id_cad_forma  = (int) $data['id_cad_forma'];
$id_cad_banco  = (int) $data['id_cad_banco'];
$id_cad_cartao = (int) $data['id_cad_cartao'];
$data_rec_pag  = !empty($data['data_rec_pag']) ? $data['data_rec_pag'] : null;

try {
    $sql = "INSERT INTO tb_lancamento (
        id_cad_tipo, id_cad_plano, desc_lanc, data_venc, valor_lanc,
        id_cad_forma, id_cad_banco, id_cad_cartao, data_rec_pag
    ) VALUES (
        :id_cad_tipo, :id_cad_plano, :desc_lanc, :data_venc, :valor_lanc,
        :id_cad_forma, :id_cad_banco, :id_cad_cartao, :data_rec_pag
    )";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_cad_tipo', $id_cad_tipo);
    $stmt->bindParam(':id_cad_plano', $id_cad_plano);
    $stmt->bindParam(':desc_lanc', $desc_lanc);
    $stmt->bindParam(':data_venc', $data_venc);
    $stmt->bindParam(':valor_lanc', $valor_lanc);
    $stmt->bindParam(':id_cad_forma', $id_cad_forma);
    $stmt->bindParam(':id_cad_banco', $id_cad_banco);
    $stmt->bindParam(':id_cad_cartao', $id_cad_cartao);
    $stmt->bindParam(':data_rec_pag', $data_rec_pag);

    $stmt->execute();

    echo json_encode(["message" => "Lançamento salvo com sucesso!"]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["message" => "Erro ao salvar: " . $e->getMessage()]);
}
