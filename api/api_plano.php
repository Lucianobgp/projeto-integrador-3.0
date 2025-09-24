<?php
//definir o cabecalho como arquivo json
header("Content-Type: application/json");

//não mostrar erros
error_reporting(~E_ALL & ~E_NOTICE & ~E_WARNING);

// Define um token estático para exemplo
define('API_TOKEN', '781e5e245d69b566979b86e28d23f2c7');

// Função para verificar se o token enviado é válido
function verificarToken($headers) {
    if (!isset($headers['Authorization'])) {
        return false;
    }

    // O formato esperado é: "Authorization: Bearer TOKEN"
    $authHeader = $headers['Authorization'];
    if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $token = $matches[1];
        return $token === API_TOKEN;
    }

    return false;
}

// Captura os headers da requisição
$headers = getallheaders();

// Verifica o token
if (!verificarToken($headers)) {
    http_response_code(401);
    print json_encode(['erro' => 'Token inválido ou ausente.']);
    exit;
}

//autoload
include_once 'autoload.php';

//capturar o tipo de ação da api: get, post, put ou delete
$method = $_SERVER['REQUEST_METHOD'];

//dados recebidos como parametro
$input = json_decode(file_get_contents('php://input'), true);

//selecionar o methodo
switch ($method) {
    case 'GET':
        //consultar
        try {
            //instanciar a classe
            $objPlano = new Plano();
            //metodo para listar autores
            $desc_plano = $objPlano->consultarPlano($input['desc_plano']);
            //gerar o JSON
            print json_encode($desc_plano);

        } catch (PDOException $e) {
            print json_encode(['error' => $e->getMessage()]);
        }
        break;

    case 'POST':
        //verificar se o autor foi passado como paramento
        if (isset($input['desc_plano'])) {
            //o autor foi passado como parametro
            try {
                //instanciar a classe
                $objPlano = new Plano();
                //invocar o método inserir
                $objPlano->inserirPlano($input['desc_plano']);
                //invocar o método inserir
                //gerar o JSON
                print json_encode(['sucesso' => true]);

            } catch (PDOException $e) {
                //erro ao inserir
                print json_encode(['error' => $e->getMessage()]);
            }

        } else {
            //o autor nao passado como parametro
            print json_encode(['error' => "O nome do Plano é obrigatório!"]);
        }
        break;

    case 'PUT':
        //alterar
        if (isset($input['desc_plano']) and isset($input['id_cad_plano'])) {
            //o autor e o id foram passados como parametros
            try {
                //instanciar da classe
                $objPlano = new Plano();
                //invocar o método alterar
                $objPlano->alterarPlano($input['id_cad_plano'], $input['desc_plano']);
                //invocar o método alterar
                //gerar o JSON
                print json_encode(['sucesso' => true]);
            } catch (PDOException $e) {
                //erro ao alterar
                print json_encode(['error' => $e->getMessage()]);
            }

        } else {
            //o autor e o id nao foi passado no parametro
            print json_encode(['error' => "O nome e o id do Plano sao obrigatório!"]);
        }
        break;

    case 'DELETE':
        //excluir
        //verificar se o id foi passado no parametro
        if (isset($input['id_cad_plano'])) {
            //o id foi passado no parametro
            try {
                //instanciar da classe
                $objPlano = new Plano();
                //invocar o método excluir
                $objPlano->excluirPlano($input['id_cad_plano']);
                //invocar o método excluir
                //gerar o JSON
                print json_encode(['sucesso' => true]);
            } catch (PDOException $e) {
                //erro ao excluir
                print json_encode(value: ['error' => $e->getMessage()]);
            }

        } else {
            //o id nao foi passado no parametro
            print json_encode(['error' => "O id_cad_plano é obrigatório!"]);
        }
        break;

    default:
        //erro
        print "METODO NÃO ENCONTRADO!";
        break;
}
