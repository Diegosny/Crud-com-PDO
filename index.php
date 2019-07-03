<?php
    include_once 'pessoa.php';

    $p = new Pessoa("localhost", "root", "", "crud");

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="estilo.css">
  <title>Index</title>
</head>
<body>
  <?php
    if(isset($_POST['nome'])) {
      //Se a pessoa clicou em cadastrar ou editar

        if(isset($_GET['id_up']) && !empty($_GET['id_up'])) {

          $id_atualizar = $_GET['id_up'];
          $nome = addslashes($_POST['nome']);
          $telefone = addslashes($_POST['telefone']);
          $email = addslashes($_POST['email']);

          if(!empty($nome) && !empty($telefone) && !empty($email)) {
            $p->atualizarDados($nome, $telefone, $email, $id_atualizar);
            header('location: index.php');
            exit();
          } else {
            ?>
            <div class="aviso">
              <img src="aviso.jpg" alt="">
              <h4> Preencha todos os campos </h4>
            </div>
          <?php
          }

        } else {

      // ------------------- CADASTRAR ----------------------
        $nome = addslashes($_POST['nome']);
        $telefone = addslashes($_POST['telefone']);
        $email = addslashes($_POST['email']);

       if(!empty($nome) && !empty($telefone) && !empty($email)) {
        if(!$p->cadastrarPessoa($nome, $telefone, $email)){
          ?>
          <div class="aviso">
            <img src="aviso.jpg" alt="">
            <h4>Email já está cadastrado</h4>
          </div>
        <?php
        }
      } else {
        ?>
            <div class="aviso">
              <img src="aviso.jpg" alt="">
              <h4>Preencha todos os campos</h4>
            </div>
          <?php
          
      }
    }
  }

    // -------------------------------------------------------------------------
    if(isset($_GET['id_up'])) { //Se a pesssoa clicou em editar
      $id_up = addslashes($_GET['id_up']);
      $res = $p->buscarPessoa($id_up);

    }
  ?>
    <section id="esquerda"> 
      <form method="POST">
        <h2>Cadastrar pessoa</h2>
        <label for="nome">Nome
          <input type="text" name="nome" id="nome" value="<? if(isset($res)) { echo $res['nome']; } ?>">
        </label>

        <label for="telefone">Telefone
          <input type="text" name="telefone" id="telefone" value="<? if(isset($res)) { echo $res['telefone']; } ?>">
        </label>

        <label for="email"> Email
          <input type="email" name="email" id="email" value="<? if(isset($res)) { echo $res['email']; } ?>">
        </label>
        
        <input type="submit" value="<? if(isset($res)) { echo 'Atualizar'; } else { echo 'Cadastrar'; }  ?>"></button>
      </form>
    </section>

    <section id="direita"> 
    <table>
          <tr id="titulo ">
            <td> Nome </td>
            <td> Telefone </td>
            <td colspan="2"> Email </td>
          </tr>

          <?php
        $dados = $p->buscarDados();
        if(count($dados) > 0 ) { //se tem pessoas cadastradas no banco
          for($i = 0; $i < count($dados); $i++) {
           echo '<tr>';
            foreach($dados[$i] as $key => $value) {
              if($key != 'id')  {
                echo '<td>'. $value. '</td>';
              }
            }
            ?> 
      <td> 
          <a href="index.php?id_up=<?php echo $dados[$i]['id'] ?>">Editar</a> 
          <a href="index.php?id=<?php echo $dados[$i]['id']; ?>">Excluir</a></td> 
      <?php
            echo '</tr>';
          }
        }  
        else {
          ?>
            <div class="aviso">
              <h4>Ainda não há registro</h4>
            </div>
          <?php
          exit();
        }

        if(isset($_GET['id'])) {
          $id_pessoa = addslashes($_GET['id']);
          $p->excluirPessoa($id_pessoa);
          header('location: index.php');
          exit();
        }
    ?>

      </table>
    </section>
</body>
</html>