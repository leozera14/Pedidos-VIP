<?php
require_once 'verifica_login.php';
require_once 'conexao.php';

error_reporting(E_ERROR);
header('Content-Type: text/html; charset=utf-8');

$usuario = intval($_GET['id']);

$query = "SELECT * FROM usuario WHERE id_usuario = '{$usuario}'";
$result = mysqli_query($connect, $query);
$usuarios = mysqli_fetch_assoc($result);
$_SESSION['id_usuario'] = $usuarios['id_usuario'];

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Altera Dados</title>
    <link rel="stylesheet" href="css/bulma.min.css" />
    <link rel="stylesheet" type="text/css" href="css/login.css">
</head>

<body>  
    <section class="hero is-success is-fullheight">
        <div class="hero-body">
            <div class="container has-text-centered">
                <div class="column is-4 is-offset-4">
                    <div class="header">
                        <h3 class="title">Alterar Dados do Usuário</h3>
                    </div>
                    <?php 
                    if(isset($_SESSION['dados_nulos'])):
                    ?>
                    <div class="notification is-warning">
                        <p>Os dados não foram totalmente preenchidos, tente novamente !</p>
                    </div>
                    <?php
                    endif;
                    unset($_SESSION['dados_nulos']);
                    ?>


                    <?php 
                    if(isset($_SESSION['senha_invalida'])):
                    ?>
                    <div class="notification is-danger">
                        <p>Senha não confere com a confirmação, por favor tente novamente.</p>
                    </div>
                    <?php
                    endif;
                    unset($_SESSION['senha_invalida']);
                    ?>


                    <?php 
                    if(isset($_SESSION['usuario_sucesso'])):
                    ?>
                    <div class="notification is-success">
                        <p>Dados atualizados com sucesso !</p>
                    </div>
                    <?php
                    endif;
                    unset($_SESSION['usuario_sucesso']);
                    ?>

                    <div class="box">
                        <form action="alterar_usuario_admin.php" method="POST">
                            <div class="field">
                                <div class="control">
                                    <b>Usuario:</b>
                                    <input type="text" value="<?= $usuarios['usuario']; ?>" class="input is-medium"
                                        name="usuario">
                                </div>
                            </div>
                            <div class="field">
                                <div class="control">
                                    <b>Nome:</b>
                                    <input type="text" value="<?= $usuarios['nome']; ?>" class="input is-medium"
                                        name="nome">
                                </div>
                            </div>
                            <div class="field">
                                <div class="control">
                                    <b>Nova Senha:</b>
                                    <input type="password" value="" class="input is-medium" name="nova_senha">
                                </div>
                            </div>
                            <div class="field">
                                <div class="control">
                                    <b>Confirmar Senha:</b>
                                    <input type="password" value="" class="input is-medium" name="confirma_senha">
                                </div>
                            </div>

                            <div class="field div-select">
                                <div class="control">
                                    <div>
                                        <b>Loja atual:</b>
                                        <b style="font-weight: bold;"><?= $usuarios['loja']?></b>
                                    </div>
                                    <div class="select is-primary">
                                        <select name="loja">
                                            <?php
                                             echo '<option selected hidden
                                             value="' .$usuarios["loja"].'"
                                             >'.$usuarios["loja"].'
                                             </option>';
                                            ?>
                                            <option value="Loja 01">Loja 01</option>
                                            <option value="Loja 02">Loja 02</option>
                                            <option value="Loja 03">Loja 03</option>
                                            <option value="Loja 04">Loja 04</option>
                                            <option value="Loja 05">Loja 05</option>
                                            <option value="Loja 06">Loja 06</option>
                                            <option value="Loja 07">Loja 07</option>
                                            <option value="Loja 10">Loja 10</option>
                                            <option value="Loja 11">Loja 11</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="submit"
                                class="button button-margin is-block is-success is-large is-fullwidth">Atualizar
                                Dados</button>
                            <a href="painel.php" class="has-text-link login">Voltar para o Painel.</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>