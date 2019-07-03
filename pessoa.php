<?php
  
    class Pessoa {
      // Atributo
      private $pdo;
      
      //Métodos
      public function __construct($host, $user, $pass, $db) {
        try {
          $this->pdo = new PDO("mysql: host=$host; dbname=$db", $user, $pass);   
        } catch(PDOExcpetion $e) {
          echo '<p>'. $e->getCode(). $e->getMessage(). '</p>';
          exit();
        }
      }

      // Função para fazer uma buscas no banco de dados
      public function buscarDados (){
        $cmd = $this->pdo->query('SELECT * FROM pessoas ORDER BY nome ');
        // O fetchAll transforma os dados em uma matriz(array)
        $res = $cmd->fetchAll(PDO:: FETCH_ASSOC);
        return $res;
      }

      // Função para cadastrar usuario
      public function cadastrarPessoa ($nome, $telefone, $email) {
        // Verificar se já possui o email
        $cad = $this->pdo->prepare('SELECT id FROM pessoas WHERE email = :e ');
        $cad->bindValue(':e', $email);
        $cad->execute();

        if ($cad->rowCount() > 0 ) {
          return false;
          exit();
        } else {
          $cad = $this->pdo->prepare('INSERT INTO pessoas (nome, telefone, email) VALUES (:n, :t, :e) ');
          $cad->bindValue(':n', $nome);  $cad->bindValue(':t', $telefone);  $cad->bindValue(':e', $email);
          $cad->execute();
          return true;
        }
      }

      // Excluir o registro por id no banco de dados
      public function excluirPessoa ($id) {
        $del = $this->pdo->prepare('DELETE FROM pessoas WHERE id = :id ');
        $del->bindValue(':id', $id);
        $del->execute();
      }


      // Buscar os dados
      public function buscarPessoa ($id) {
        $busca = $this->pdo->prepare('SELECT * FROM pessoas WHERE id = :id ');
        $busca->bindValue(':id', $id);
        $busca->execute();
        
        $list = $busca->fetch(PDO:: FETCH_ASSOC); 
        return $list;
      }

      // Atualizar os dados
      public function atualizarDados($nome, $telefone, $email, $id) {

        $cmd = $this->pdo->prepare('UPDATE pessoas SET nome = :n, telefone = :t, email = :e WHERE id = :id ');
        $cmd->bindValue(':n', $nome); $cmd->bindValue(':t', $telefone); $cmd->bindValue(':e', $email); $cmd->bindValue(':id', $id);
        $cmd->execute();
        return true;
      }
    }
  