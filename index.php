<?php
include_once('Conexao.php');
include_once('DAO/DaoContato.php');
include_once('Classes/Contatos.php');


$conexao = PdoConexao::getInstancia();
$daoContato = new DaoContato($conexao);

// Verifica se o formulário de cadastro foi submetido
if (isset($_POST['cadastrar'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $contato = new Contato($nome, $email, $telefone);
    $daoContato->create($contato);
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>CRUD com Classe DAO e Conexão PDO</title>
    <?php include 'style.php'; ?>
</head>

<body>
    <div class="container">
        <h1>CRUD com Classe DAO e Conexão PDO</h1>
        <div class="child" style="position: fixed; margin-top: 850px;">

            <form method="post" action="" class="form card">
                <div class="card_header">
                    <h1 class="form_heading">Cadastrar Contato</h1>
                </div>
                <div class="field">
                    <label for="Nome">Nome</label>
                    <input type="text" name="nome" placeholder="Nome" class="input" required><br><br>
                </div>
                <div class="field">
                    <label for="Email">Email</label>
                    <input type="email" name="email" placeholder="E-mail" class="input" required><br><br>
                </div>
                <div class="field">
                    <label for="Telefone">Telefone</label>
                    <input type="text" name="telefone" placeholder="Telefone" class="input" required><br><br>
                </div>
                <div class="field">
                    <input type="submit" name="cadastrar" value="Cadastrar" class="button">
                </div>
            </form>

        </div>
        <a href="listar-contato.php" class="b-operacao" style="margin-top: 160px; text-align: center; position: absolute;">Lista de Usuarios</a>
    </div>

</body>

</html>
