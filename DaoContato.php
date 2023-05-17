<?php
include('Interface.php');
include_once('Conexao.php');

class DaoContato implements iDaoModeCrud
{

   private $instanciaConexaoPdoAtiva;
   private $tabela;

   public function __construct()
   {
      $this->instanciaConexaoPdoAtiva = PdoConexao::getInstancia();
      $this->tabela = 'contatos';
   }
   /**
    *
    * create: Insere informações no banco de dados
    *
    * @param object $objeto
    * @return boolean
    */
   public function create($objeto)
   {
      $id = $this->getNewIdContato();
      $nome = $objeto->getNome();
      $email = $objeto->getEmail();
      $telefone = $objeto->getTelefone();
      $sqlStmt = "INSERT INTO {$this->tabela} (ID, NOME, EMAIL, TELEFONE) VALUES (:id, :nome, :email, :telefone)";
      try {
         $operacao = $this->instanciaConexaoPdoAtiva->prepare($sqlStmt);
         $operacao->bindValue(':id', $id, PDO::PARAM_INT);
         $operacao->bindValue(':nome', $nome, PDO::PARAM_STR);
         $operacao->bindValue(':email', $email, PDO::PARAM_STR);
         $operacao->bindValue(':telefone', $telefone, PDO::PARAM_STR);
         if ($operacao->execute()) {
            if ($operacao->rowCount() > 0) {
               $objeto->setId($id);
               return true;
            } else {
               return false;
            }
         } else {
            return false;
         }
      } catch (PDOException $excecao) {
         echo $excecao->getMessage();
      }
   }
   /**
    *
    * read: Retorna um objeto refletindo um contato
    *
    * @param int $id
    * @return object
    */
   public function read($id)
   {
      $sqlStmt = "SELECT * from {$this->tabela} WHERE ID=:id";
      try {
         $operacao = $this->instanciaConexaoPdoAtiva->prepare($sqlStmt);
         $operacao->bindValue(':id', $id, PDO::PARAM_INT);
         $operacao->execute();
         $getRow = $operacao->fetch(PDO::FETCH_OBJ);
         if (!$getRow) {
            return null;
         }
         $nome = $getRow->NOME;
         $email = $getRow->EMAIL;
         $telefone = $getRow->TELEFONE;
         $objeto = new Contato($nome, $email, $telefone);
         $objeto->setId($id);
         return $objeto;
      } catch (PDOException $excecao) {
         echo $excecao->getMessage();
      }
   }

   /**
    *
    * update: atualiza um contato
    *
    * @param object $objeto
    * @return boolean
    */
   public function update($objeto)
   {
      $id = $objeto->getId();
      $nome = $objeto->getNome();
      $email = $objeto->getEmail();
      $telefone = $objeto->getTelefone();
      $sqlStmt = "UPDATE $this->tabela SET NOME=:nome, EMAIL=:email, TELEFONE=:telefone WHERE ID=:id";
      try {
         $operacao = $this->instanciaConexaoPdoAtiva->prepare($sqlStmt);
         $operacao->bindValue(':id', $id);
         $operacao->bindValue(':nome', $nome);
         $operacao->bindValue(':email', $email);
         $operacao->bindValue(':telefone', $telefone);


         if ($operacao->execute()) {
            $result = $operacao->fetch(PDO::FETCH_ASSOC);
         } else {
            return false;
         }
      } catch (PDOException $excecao) {
         echo $excecao->getMessage();
      }
   }

   /**
    *
    * DELETE exclui um contato no banco de dados conforme informado por id
    *
    * @param int $id
    * @return boolean
    */
   public function delete($id)
   {
      $sqlStmt = "DELETE FROM {$this->tabela} WHERE ID=:id";
      try {
         $operacao = $this->instanciaConexaoPdoAtiva->prepare($sqlStmt);
         $operacao->bindValue(':id', $id, PDO::PARAM_INT);
         if ($operacao->execute()) {
            if ($operacao->rowCount() > 0) {
               return true;
            } else {
               return false;
            }
         } else {
            return false;
         }
      } catch (PDOException $excecao) {
         echo $excecao->getMessage();
      }
   }

   /**
    *
    * getNewIdContato retorna um novo Id para novos registros
    *
    * @return int
    * @throws Exception
    */
   private function getNewIdContato()
   {
      $sqlStmt = "SELECT MAX(ID) AS ID FROM {$this->tabela}";
      try {
         $operacao = $this->instanciaConexaoPdoAtiva->prepare($sqlStmt);
         if ($operacao->execute()) {
            if ($operacao->rowCount() > 0) {
               $getRow = $operacao->fetch(PDO::FETCH_OBJ);
               $idReturn = (int) $getRow->ID + 1;
               return $idReturn;
            } else {
               throw new Exception('Ocorreu um problema com o banco de dados');
               exit();
            }
         } else {
            throw new Exception('Ocorreu um problema com o banco de dados');
            exit();
         }
      } catch (PDOException $excecao) {
         echo $excecao->getMessage();
      }
   }

   /**
    *
    * ListarContatos lista todos os contatos registrados
    *
    */
   public function listarContatos()
   {
      $sql = "SELECT id, nome, email, telefone FROM contatos";
      $stmt = $this->instanciaConexaoPdoAtiva->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $contatos = array();
      foreach ($result as $row) {
         $contato = new Contato($row['nome'], $row['email'], $row['telefone']);
         $contato->setId($row['id']);
         $contatos[] = $contato;
      }

      return $contatos;
   }
}
