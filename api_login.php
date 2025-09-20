<?php
header("Access-Control-Allow-Origin: http://gzfinanceiropessoal.com.br");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Método não permitido."]);
    exit();
}

$servername = "db_financaspi.mysql.dbaas.com.br";
$username   = "db_financaspi";
$password   = "Gzfinanceiro1@";
$dbname     = "db_financaspi";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("DB ERROR: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Erro interno no servidor."]);
    exit();
}

$action = $_POST['action'] ?? '';
switch ($action) {
    case 'register':
        handleRegister($conn, $_POST);
        break;
    case 'login':
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $senha = $_POST['senha'] ?? '';
        if (!$email || empty($senha)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "E-mail ou senha inválidos."]);
            exit();
        }
        handleLogin($conn, $email, $senha);
        break;
    default:
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Ação inválida."]);
}

function handleRegister($conn, $data) {
    try {
        $nome = trim($data['nome']);
        $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        $senha = $data['senha'];

        if (!$email || empty($nome) || empty($senha)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Dados inválidos."]);
            return;
        }

        $sql = "SELECT id_cad_usuario FROM tb_cad_usuario WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->fetch()) {
            http_response_code(409);
            echo json_encode(["status" => "error", "message" => "E-mail já cadastrado."]);
            return;
        }

        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "INSERT INTO tb_cad_usuario (nome_usuario, email, senha) VALUES (:nome, :email, :senha)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':nome' => $nome, ':email' => $email, ':senha' => $senha_hash]);

        http_response_code(201);
        echo json_encode(["status" => "ok", "message" => "Usuário cadastrado com sucesso."]);
    } catch (PDOException $e) {
        error_log("REGISTER ERROR: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Erro ao cadastrar usuário."]);
    }
}

function handleLogin($conn, $email, $senha) {
    try {
        $sql = "SELECT id_cad_usuario, nome_usuario, senha FROM tb_cad_usuario WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($senha, $user['senha'])) {
            echo json_encode([
                "status" => "ok",
                "message" => "Login bem-sucedido!",
                "user" => [
                    "id" => $user['id_cad_usuario'],
                    "nome" => $user['nome_usuario']
                ]
            ]);
        } else {
            http_response_code(401);
            echo json_encode(["status" => "error", "message" => "E-mail ou senha inválidos."]);
        }
    } catch (PDOException $e) {
        error_log("LOGIN ERROR: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Erro ao fazer login."]);
    }
}
