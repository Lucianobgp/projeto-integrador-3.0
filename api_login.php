<?php
// Configura os cabeçalhos para permitir CORS e retornar JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Configurações do banco de dados (substitua com suas credenciais)
$servername = "db_financaspi.mysql.dbaas.com.br";
$username = "db_financaspi";
$password = "Gzfinanceiro1@";
$dbname = "db_financaspi";

// Conexão com o banco de dados
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(["message" => "Erro de conexão com o banco de dados: " . $e->getMessage()]);
    exit();
}

// Pega o corpo da requisição JSON e o tipo de ação (login ou cadastro)
$data = json_decode(file_get_contents("php://input"), true);
$action = isset($data['action']) ? $data['action'] : '';

// Lógica para diferentes ações
switch ($action) {
    case 'register':
        handleRegister($conn, $data);
        break;
    case 'login':
        handleLogin($conn, $data);
        break;
    default:
        http_response_code(400); // Requisição inválida
        echo json_encode(["message" => "Ação não especificada."]);
        break;
}

// --- Funções de manipulação ---

function handleRegister($conn, $data) {
    if (!isset($data['nome']) || !isset($data['email']) || !isset($data['senha'])) {
        http_response_code(400);
        echo json_encode(["message" => "Nome, e-mail e senha são obrigatórios."]);
        return;
    }

    $nome = $data['nome'];
    $email = $data['email'];
    $senha_em_texto_puro = $data['senha'];

    // Criptografa a senha antes de salvar
    $senha_hash = password_hash($senha_em_texto_puro, PASSWORD_DEFAULT);

    try {
        // Verifica se o e-mail já existe
        $sql = "SELECT id_cad_usuario FROM tb_cad_usuario WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            http_response_code(409); // Conflito (e-mail já existe)
            echo json_encode(["message" => "Este e-mail já está cadastrado."]);
            return;
        }

        // Insere o novo usuário
        $sql = "INSERT INTO tb_cad_usuario (nome_usuario, email, senha) VALUES (:nome, :email, :senha)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha_hash);
        $stmt->execute();

        http_response_code(201); // Criado
        echo json_encode(["message" => "Usuário cadastrado com sucesso!"]);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(["message" => "Erro ao cadastrar usuário: " . $e->getMessage()]);
    }
}

function handleLogin($conn, $data) {
    // Adicione esta linha para ver o que a API está recebendo
    file_put_contents('debug.log', "Dados recebidos: " . print_r($data, true));
    if (!isset($data['email']) || !isset($data['senha'])) {
        http_response_code(400);
        echo json_encode(["message" => "E-mail e senha são obrigatórios."]);
        return;
    }

    $email = $data['email'];
    $senha_em_texto_puro = $data['senha'];

    try {
        // Busca o usuário pelo e-mail
        $sql = "SELECT id_cad_usuario, nome_usuario, senha FROM tb_cad_usuario WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se o usuário foi encontrado e se a senha está correta
        if ($user && password_verify($senha_em_texto_puro, $user['senha'])) {
            http_response_code(200);
            echo json_encode(["message" => "Login bem-sucedido!", "user" => ["id" => $user['id_cad_usuario'], "nome" => $user['nome_usuario']]]);
        } else {
            http_response_code(401);
            echo json_encode(["message" => "E-mail ou senha inválidos."]);
        }
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(["message" => "Erro ao fazer login: " . $e->getMessage()]);
    }
}
?>