<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cadastro - Pedidos VIP</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    <link rel="stylesheet" href="css/bulma.min.css" />
    <link rel="stylesheet" type="text/css" href="css/login.css">
</head>

<body>

    <section class="hero is-success is-fullheight">
        <div class="hero-body">
            <div class="container has-text-centered">
                <div class="column is-4 is-offset-4">
                    <div class="header">
                        <h3 class="title">Sistema de Cadastro</h3>
                    </div>
                    <?php
                    if (isset($_SESSION['dados_invalidos'])):
                    ?>

                    <div class="notification is-danger">
                        <p>Dados inválidos ou insuficientes. Favor preencher todos os campos !</p>
                    </div>

                    <?php
                    endif;
                    unset($_SESSION['dados_invalidos']);
                    ?>
                    
                    <?php
                    if (isset($_SESSION['usuario_existe'])):
                    ?>

                    <div class="notification is-danger">
                        <p>O usuário já existe. Informe outro e tente novamente.</p>
                    </div>

                    <?php
                    endif;
                    unset($_SESSION['usuario_existe']);
                    ?>

                    <div class="box">
                        <form action="cadastrar.php" method="POST">
                            <div class="field">
                                <div class="control">
                                    <input name="nome" type="text" class="input is-medium" placeholder="Nome" autofocus>
                                </div>
                            </div>
                            <div class="field">
                                <div class="control">
                                    <input name="usuario" type="text" class="input is-medium" placeholder="Usuário">
                                </div>
                            </div>
                            <div class="field">
                                <div class="control">
                                    <input name="senha" class="input is-medium" type="password" placeholder="Senha">
                                </div>
                            </div>

                            <div class="field div-select">
                                <div class="control">
                                    <div>
                                        <b>Selecione sua Loja:</b>
                                    </div>
                                    <div class="select is-primary">
                                        <select name="loja">
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
                                class="button button-margin is-block is-success is-large is-fullwidth">Cadastrar</button>
                            <a href="index.php" class="has-text-link login">Já possui usuário ? Faça o login clicando
                                aqui.</a>
                            <br>
                            <br>
                            <?php
                            if (isset($_SESSION['admin'])):
                            ?>
                            <a href="painel.php" class="has-text-link login">Voltar ao Painel Administrativo.</a>

                            <?php
                            endif;
                            unset($_SESSION['admin']);
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>