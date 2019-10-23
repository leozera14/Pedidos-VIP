<?php
session_start();
include 'conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - Pedidos VIP</title>
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
                        <h3 class="title">Sistema de Login</h3>
                    </div>

                    <?php
                        if (isset($_SESSION['login_vazio'])):
                    ?>

                    <div class="notification is-danger">
                        <p>Informe o seu usuário e senha !</p>
                    </div>

                    <?php
                    endif;
                        unset($_SESSION['login_vazio']);
                    ?>

                    <?php
                        if (isset($_SESSION['nao_autenticado'])):
                    ?>

                    <div class="notification is-danger">
                        <p>ERRO: Usuário ou senha inválidos</p>
                    </div>

                    <?php
                    endif;
                        unset($_SESSION['nao_autenticado']);
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

                    
                    <?php
                    if (isset($_SESSION['status_cadastro'])):
                    ?>

                    <div class="notification is-success">
                        <p>Cadastro efetuado com sucesso!</p>
                        <p>Faça login informando o seu usuário e senha !</p>
                    </div>

                    <?php
                    endif;
                    unset($_SESSION['status_cadastro']);
                    ?>


                    <div class="box">
                        <form action="login.php" method="POST">
                            <div class="field">
                                <div class="control">
                                    <input name="usuario" name="text" class="input is-medium" placeholder="Seu usuário"
                                        autofocus="">
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <input name="senha" class="input is-medium" type="password" placeholder="Sua senha">
                                </div>
                            </div>
                            <button type="submit"
                                class="button button-margin is-block is-link is-medium is-fullwidth">Entrar</button>
                            <a href="cadastro.php"
                                class="button is-block is-success is-medium is-fullwidth">Cadastrar-se</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>