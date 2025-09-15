<?php
// incluir classe conexão
include_once 'Conexao.class.php';

// class Usuário
class Usuario extends Conexao
{
    // atributos
    private $id_cad_usuario;
    private $nome_usuario;
    private $email;
    private $senha;

    // getters & setters
    public function getIdCadUsuario()
    {
        return $this->id_cad_usuario;
    }

    public function setIdCadUsuario($id_cad_usuario)
    {
        $this->id_cad_usuario = $id_cad_usuario;
    }

    public function getNomeUsuario()
    {
        return $this->nome_usuario;
    }

    public function setNomeUsuario($nome_usuario)
    {
        $this->nome_usuario = $nome_usuario;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getSenha()
    {
        return $this->senha;
    }

    public function setSenha($senha)
    {
        $this->senha = $senha;
    }

    // Método Inserir Usuário (AJUSTADO PARA SEGURANÇA)
    public function inserirUsuario($nome_usuario, $email, $senha)
    {
        // Criptografa a senha antes de setar o atributo
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $this->setNomeUsuario($nome_usuario);
        $this->setEmail($email);
        $this->setSenha($senha_hash); // Seta a senha criptografada

        //montar query
        $sql = "INSERT INTO tb_cad_usuario (id_cad_usuario, nome_usuario, email, senha)
        VALUES (NULL, :nome_usuario, :email, :senha)";

        //executa a query
        try {
            //conectar com o banco
            $bd = $this->conectar();
            //preparar o sql
            $query = $bd->prepare($sql);
            //blidagem dos dados
            $query->bindValue(':nome_usuario', $this->getNomeUsuario(), PDO::PARAM_STR);
            $query->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
            $query->bindValue(':senha', $this->getSenha(), PDO::PARAM_STR);

            //excutar a query
            $query->execute();
            //retorna o resultado
            return true;

        } catch (PDOException $e) {
            return false;
        }
    }

    //metodo consultar usuário
    public function consultarUsuario($nome_usuario)
    {
        //setar os atributos
        $this->setNomeUsuario($nome_usuario);

        //montar query
        $sql = "SELECT * FROM tb_cad_usuario where true ";

        //vericar se o nome é nulo
        if ($this->getNomeUsuario() != null) {
            $sql .= " and nome_usuario like :nome_usuario";
        }

        //ordenar a tabela
        $sql .= " order by nome_usuario ";

        //executa a query
        try {
            //conectar com o banco
            $bd = $this->conectar();
            //preparar o sql
            $query = $bd->prepare($sql);
            //blidagem dos dados
            if ($this->getNomeUsuario() != null) {
                $this->setNomeUsuario("%" . $nome_usuario . "%");
                $query->bindValue(':nome_usuario', $this->getNomeUsuario(), PDO::PARAM_STR);
            }
            //excutar a query
            $query->execute();
            //retorna o resultado
            $resultado = $query->fetchAll(PDO::FETCH_OBJ);
            return $resultado;

        } catch (PDOException $e) {
            return false;
        }
    }

    //método alterar usuario (AJUSTADO PARA SEGURANÇA)
    public function alterarUsuario($id_cad_usuario, $nome_usuario, $email, $senha)
    {
        $this->setIdCadUsuario($id_cad_usuario);
        $this->setNomeUsuario($nome_usuario);
        $this->setEmail($email);

        // Criptografa a senha antes de setar o atributo
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $this->setSenha($senha_hash);

        //montar query
        $sql = "UPDATE tb_cad_usuario SET nome_usuario = :nome_usuario, email = :email, senha = :senha WHERE id_cad_usuario = :id_cad_usuario";

        //executa a query
        try {
            //conectar com o banco
            $bd = $this->conectar();
            //preparar o sql
            $query = $bd->prepare($sql);
            //blidagem dos dados
            $query->bindValue(':id_cad_usuario', $this->getIdCadUsuario(), PDO::PARAM_INT);
            $query->bindValue(':nome_usuario', $this->getNomeUsuario(), PDO::PARAM_STR);
            $query->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
            $query->bindValue(':senha', $this->getSenha(), PDO::PARAM_STR);
            //excutar a query
            $query->execute();
            //retorna o resultado
            return true;

        } catch (PDOException $e) {
            return false;
        }
    }

    //método excluir usuário
    public function excluirUsuario($id_cad_usuario)
    {
        //setar os atributos
        $this->setIdCadUsuario($id_cad_usuario);

        //montar query
        $sql = "DELETE FROM tb_cad_usuario WHERE id_cad_usuario = :id_cad_usuario";

        //executa a query
        try {
            //conectar com o banco
            $bd = $this->conectar();
            //preparar o sql
            $query = $bd->prepare($sql);
            //blindagem dos dados
            $query->bindValue(':id_cad_usuario', $this->getIdCadUsuario(), PDO::PARAM_INT);
            //excutar a query
            $query->execute();
            //retorna o resultado
            print "Excluido";
            return true;

        } catch (PDOException $e) {
            return false;
        }
    }

    //método validar login (AJUSTADO PARA SEGURANÇA)
    public function validarLogin($email, $senha)
{
    // Busca o usuário apenas pelo email
    $sql = "SELECT senha FROM tb_cad_usuario WHERE email = :email";

    try {
        $bd = $this->conectar();
        $query = $bd->prepare($sql);
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);

        // Se o usuário existe, verifica a senha
        if ($user) {
            // A função password_verify compara a senha em texto puro com o hash
            if (password_verify($senha, $user['senha'])) {
                return true;
            }
        }

        return false;
    } catch (PDOException $e) {
        return false;
    }
}

    // metodo validarEmail
    public function validarEmail($email)
    {
        //setar os dados
        $this->setEmail($email);

        //sql
        $sql = "SELECT count(*) as quantidade FROM tb_cad_usuario WHERE email= :email";

        try {
            //conectar com o banco
            $bd = $this->conectar();
            //preparar o sql
            $query = $bd->prepare($sql);
            //blidagem dos dados
            $query->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
            //excutar a query
            $query->execute();
            //retorna o resultado
            $resultado = $query->fetchAll(PDO::FETCH_OBJ);
            //verificar o resultado
            foreach ($resultado as $key => $valor) {
                $quantidade = $valor->quantidade;
            }
            //testar quantidade
            if ($quantidade == 1) {
                return true;
            } else {
                return false;
            }

        } catch (PDOException $e) {
            return false;
        }
    }

    //Alterar Senha (AJUSTADO PARA SEGURANÇA)
    public function alterarSenha($email, $senha)
    {
        // Criptografa a nova senha antes de setar o atributo
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        //setar os dados
        $this->setEmail($email);
        $this->setSenha($senha_hash);

        //montar query
        $sql = "update tb_cad_usuario set senha= :senha where email= :email";

        try {
            //conectar com o banco
            $bd = $this->conectar();
            //preparar o sql
            $query = $bd->prepare($sql);
            //blidagem dos dados
            $query->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
            $query->bindValue(':senha', $this->getSenha(), PDO::PARAM_STR);
            //excutar a query
            $query->execute();
            //retorna o resultado
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}