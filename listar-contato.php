<?php
include_once('Conexao.php');
include_once('DAO/DaoContato.php');
include_once('Classes/Contatos.php');

$conexao = PdoConexao::getInstancia();
$daoContato = new DaoContato($conexao);

// Verifica se a função de excluir foi submetida
if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $daoContato->delete($id);
}

// Verifica se a opção de update foi submetida
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $contato = $daoContato->read($id);
?>
    <div class="container">
        <div class="child" style="position: fixed; margin-top: 850px;">
            <form method="post" action="" class="form card">
                <div class="card_header">
                    <div class="field">
                        <label for="Nome">Nome</label>
                        <input type="hidden" name="id" value="<?= $contato->getId() ?>">
                        <input type="text" id="nome" name="nome" class="input" value="<?= $contato->getNome() ?>"><br>
                    </div>
                    <div class="field">
                        <label for="Nome">Email</label>
                        <input type="email" id="email" name="email" class="input" value="<?= $contato->getEmail() ?>"><br>
                    </div>
                    <div class="field">
                        <label for="Nome">Telefone</label>
                        <input type="tel" id="telefone" name="telefone" class="input" value="<?= $contato->getTelefone() ?>"><br>
                    </div>
                    <input type="submit" name="editar" value="Salvar" class="button">
                </div>
            </form>
        </div>
    </div>
<?php } elseif (isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $contato = new Contato($nome, $email, $telefone);
    $contato->setId($id);
    // verifica se todos os campos foram preenchidos
    if(empty($nome) || empty($email) || empty($telefone)) {
        echo "Por favor, preencha todos os campos.";
    } else {
    try {
        $daoContato->update($contato);
    } catch (Exception $e) {
        echo "Erro ao atualizar o contato: " . $e->getMessage();
    }
}
 }
// Lista os registros para serem exibidos na tabela
$contatos = $daoContato->listarContatos();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>CRUD com Classe DAO e Conexão PDO</title>
    <?php include 'style.php'; ?>
</head>

<body>
    <h1 style="text-align: center;">CRUD com Classe DAO e Conexão PDO</h1>

    <a href="index.php" class=" b-operacao" 
    style=" display: flex;
    justify-content: center;
     margin-bottom:20px; margin-left:700px;">
     Cadastrar</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contatos as $contato) { ?>
                <tr>
                    <td><?= $contato->getId() ?></td>
                    <td><?= $contato->getNome() ?></td>
                    <td><?= $contato->getEmail() ?></td>
                    <td><?= $contato->getTelefone() ?></td>
                    <td>
                        <form method="post" action="">
                            <input type="hidden" name="id" value="<?= $contato->getId() ?>">
                            <input type="submit" name="update" value="Editar" class="button">
                        </form>
                        <form method="post" action="?excluir=<?= $contato->getId() ?>" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                            <input type="submit" value="Excluir" class="button">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</body>

</html>
