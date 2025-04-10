<?php
//incluir classe conexao
include_once 'Conexao.class.php';

//classe cartão
class Lancamento extends Conexao
{
    //atributos
    private $id_lanc;
    private $id_cad_tipo;
    private $id_cad_plano;
    private $desc_lanc;
    private $data_venc;
    private $valor_lanc;
    private $id_cad_forma;
    private $id_cad_banco;
    private $id_cad_cartao;
    private $data_rec_pag;

    //getters e setters

    public function getId_lanc()
    {
        return $this->id_lanc;
    }

    public function setId_lanc($value)
    {
        $this->id_lanc = $value;
    }

    public function getId_cad_tipo()
    {
        return $this->id_cad_tipo;
    }

    public function setId_cad_tipo($value)
    {
        $this->id_cad_tipo = $value;
    }

    public function getId_cad_plano()
    {
        return $this->id_cad_plano;
    }

    public function setId_cad_plano($value)
    {
        $this->id_cad_plano = $value;
    }

    public function getDesc_lanc()
    {
        return $this->desc_lanc;
    }

    public function setDesc_lanc($value)
    {
        $this->desc_lanc = $value;
    }

    public function getData_venc()
    {
        return $this->data_venc;
    }

    public function setData_venc($value)
    {
        $this->data_venc = $value;
    }

    public function getValor_lanc()
    {
        return $this->valor_lanc;
    }

    public function setValor_lanc($value)
    {
        $this->valor_lanc = $value;
    }

    public function getId_cad_forma()
    {
        return $this->id_cad_forma;
    }

    public function setId_cad_forma($value)
    {
        $this->id_cad_forma = $value;
    }

    public function getId_cad_banco()
    {
        return $this->id_cad_banco;
    }

    public function setId_cad_banco($value)
    {
        $this->id_cad_banco = $value;
    }

    public function getId_cad_cartao()
    {
        return $this->id_cad_cartao;
    }

    public function setId_cad_cartao($value)
    {
        $this->id_cad_cartao = $value;
    }

    public function getData_rec_pag()
    {
        return $this->data_rec_pag;
    }

    public function setData_rec_pag($value)
    {
        $this->data_rec_pag = $value;
    }

    //método inserir lançamento
    public function inserirLancamento($id_cad_tipo, $id_cad_plano, $desc_lanc, $data_venc, $valor_lanc, $id_cad_forma, $id_cad_banco, $id_cad_cartao, $data_rec_pag)
    {
        //setar os atributos
        $this->setId_cad_tipo($id_cad_tipo);
        $this->setId_cad_plano($id_cad_plano);
        $this->setDesc_lanc($desc_lanc);
        $this->setData_venc($data_venc);
        $this->setValor_lanc($valor_lanc);
        $this->setId_cad_forma($id_cad_forma);
        $this->setId_cad_banco($id_cad_banco);
        $this->setId_cad_cartao($id_cad_cartao);
        $this->setData_rec_pag($data_rec_pag);

        //montar query
        $sql = "INSERT INTO tb_lancamento (id_lanc, id_cad_tipo, id_cad_plano, desc_lanc, data_venc, valor_lanc, id_cad_forma, id_cad_banco, id_cad_cartao, data_rec_pag) VALUES (NULL, :id_cad_tipo, :id_cad_plano, :desc_lanc, :data_venc, :valor_lanc, :id_cad_forma, :id_cad_banco, :id_cad_cartao, :data_rec_pag)";

        //executa a query
        try {
            //conectar com o banco
            $bd = $this->conectar();
            //preparar o sql
            $query = $bd->prepare($sql);
            //blindagem dos dados
            $query->bindValue(':id_cad_tipo', $this->getId_cad_tipo(), PDO::PARAM_INT);
            $query->bindValue(':id_cad_plano', $this->getId_cad_plano(), PDO::PARAM_INT);
            $query->bindValue(':desc_lanc', $this->getDesc_lanc(), PDO::PARAM_STR);
            $query->bindValue(':data_venc', $this->getData_venc(), PDO::PARAM_STR);
            $query->bindValue(':valor_lanc', $this->getValor_lanc(), PDO::PARAM_STR);
            $query->bindValue(':id_cad_forma', $this->getId_cad_forma(), PDO::PARAM_INT);
            $query->bindValue(':id_cad_banco', $this->getId_cad_banco(), PDO::PARAM_INT);
            $query->bindValue(':id_cad_cartao', $this->getId_cad_cartao(), PDO::PARAM_INT);
            $query->bindValue(':data_rec_pag', $this->getData_rec_pag(), PDO::PARAM_STR);
            //excutar a query
            $query->execute();
            //retorna o resultado
            //print "Inserido";
            return true;

        } catch (PDOException $e) {
            //print "Erro ao inserir";
            return false;
        }
    }

    //metodo consultar
    public function consultarLancamento($desc_lanc)
    {
        //setar os atributos
        $this->setDesc_lanc($desc_lanc);

        //montar query
        $sql = 
        "   SELECT 
            id_lanc, tt.desc_tipo, tp.desc_plano, desc_lanc, data_venc, valor_lanc, tf.desc_forma, tb.nome_banco, tc.nome_cartao, data_rec_pag
            FROM 
            tb_lancamento as tl
            left join tb_cad_tipo as tt on tt.id_cad_tipo = tl.id_cad_tipo
            left join tb_cad_plano as tp on tp.id_cad_plano = tl.id_cad_plano
            left join tb_cad_forma as tf on tf.id_cad_forma = tl.id_cad_forma
            left join tb_cad_banco as tb on tb.id_cad_banco = tl.id_cad_banco
            left join tb_cad_cartao as tc on tc.id_cad_cartao = tl.id_cad_cartao
            WHERE true
        ";

        //verificar se a descrição do lançamento é nula
        if ($this->getDesc_lanc() != null) {
            $sql .= " and desc_lanc like :desc_lanc";
        }
        //vericar se o nome é nulo
        // if ($this->getId_bandeira() != null) {
        //     $sql .= " and id_bandeira like :id_cad_band";
        // }

        //ordenar a tabela
        $sql .= " order by id_lanc desc";

        //executa a query
        try {
            //conectar com o banco
            $bd = $this->conectar();
            //preparar o sql
            $query = $bd->prepare($sql);
            //blindagem dos dados
            if ($this->getDesc_lanc() != null) {
                $this->setDesc_lanc("%" . $desc_lanc . "%");
                $query->bindValue(':desc_lanc', $this->getDesc_lanc(), PDO::PARAM_STR);
            }

            //excutar a query
            $query->execute();
            //retorna o resultado
            $resultado = $query->fetchAll(PDO::FETCH_OBJ);
            return $resultado;

        } catch (PDOException $e) {
            //print "Erro ao consultar";
            return false;
        }

    }

    //método alterar lançamento
    // public function alterarLancamento($id_lanc, $id_cad_tipo, $id_cad_plano, $desc_lanc, $data_venc, $valor_lanc, $id_cad_forma, $id_cad_banco, $id_cad_cartao, $data_rec_pag)
    // {
    public function alterarLancamento($id_lanc, $desc_lanc)
    {
        //setar os atributos
        $this->setId_lanc($id_lanc);
        // $this->setId_cad_tipo($id_cad_tipo);
        // $this->setId_cad_plano($id_cad_plano);
        $this->setDesc_lanc($desc_lanc);
        // $this->setData_venc($data_venc);
        // $this->setValor_lanc($valor_lanc);
        // $this->setId_cad_forma($id_cad_forma);
        // $this->setId_cad_banco($id_cad_banco);
        // $this->setId_cad_cartao($id_cad_cartao);
        // $this->setData_rec_pag($data_rec_pag);

        //montar query
        $sql = 
        "   UPDATE 
            tb_lancamento 
            SET 
            id_cad_tipo = :id_cad_tipo,
            id_cad_plano = :id_cad_plano,
            desc_lanc = :desc_lanc,
            data_venc = :data_venc,
            valor_lanc = :valor_lanc,
            id_cad_forma = :id_cad_forma,
            id_cad_banco = :id_cad_banco,
            id_cad_cartao = :id_cad_cartao,
            data_rec_pag = :data_rec_pag
            WHERE 
            id_lanc = :id_lanc
        ";

        //executa a query
        try {
            //conectar com o banco
            $bd = $this->conectar();
            //preparar o sql
            $query = $bd->prepare($sql);
            //blidagem dos dados
            $query->bindValue(':id_lanc', $this->getId_lanc(), PDO::PARAM_INT);
            $query->bindValue(':id_cad_tipo', $this->getId_cad_tipo(), PDO::PARAM_INT);
            $query->bindValue(':id_cad_plano', $this->getId_cad_plano(), PDO::PARAM_INT);
            $query->bindValue(':desc_lanc', $this->getDesc_lanc(), PDO::PARAM_STR);
            $query->bindValue(':data_venc', $this->getData_venc(), PDO::PARAM_STR);
            $query->bindValue(':valor_lanc', $this->getValor_lanc(), PDO::PARAM_STR);
            $query->bindValue(':id_cad_forma', $this->getId_cad_forma(), PDO::PARAM_INT);
            $query->bindValue(':id_cad_banco', $this->getId_cad_banco(), PDO::PARAM_INT);
            $query->bindValue(':id_cad_cartao', $this->getId_cad_cartao(), PDO::PARAM_INT);
            $query->bindValue(':data_rec_pag', $this->getData_rec_pag(), PDO::PARAM_STR);
            //excutar a query
            $query->execute();
            //retorna o resultado
            //print "Alterado";
            return true;

        } catch (PDOException $e) {
            //print "Erro ao alterar";
            return false;
        }
    }

    //método excluir cartao
    public function excluirLancamento($id_lanc)
    {
        //setar os atributos
        $this->setId_lanc($id_lanc);

        //montar query
        $sql = "DELETE FROM tb_lancamento WHERE id_lanc = :id_lanc";

        //executa a query
        try {
            //conectar com o banco
            $bd = $this->conectar();
            //preparar o sql
            $query = $bd->prepare($sql);
            //blindagem dos dados
            $query->bindValue(':id_lanc', $this->getId_lanc(), PDO::PARAM_INT);
            //excutar a query
            $query->execute();
            //retorna o resultado
            //print "Excluido";
            return true;

        } catch (PDOException $e) {
            // print "Erro ao excluir: " . $e->getMessage();
            return false;
        }
    }
}
