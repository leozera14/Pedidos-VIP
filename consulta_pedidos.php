<?php
session_start();
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Essas duas linhas são do Bootstrap, deixa a formatação mais 'bonita' -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css"/>
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Consulta de Pedidos</title>
    <style>
        footer {
            width: 100%;
            height: 100px;
            position: absolute;
            bottom: 0;
            left: 0;
        }

        #produtos {
            border-bottom: 1px solid #8d66b39e;
        }

        #pedido {
            max-width: 200px;
        }

        #consulta-button {
            width: 100px;
            margin-top: 15px;
        }

        .alert-edit {
            max-width: 420px;
            font-size: 13px;
            font-weight: bold;
        
        }
    </style>
</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
<!--<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>-->
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/jquery.mask.js"></script>
<script src="js/bootstrap-select.js"></script>
<script src="js/script.js"></script>

<body>

<div class="area"></div>
<nav class="main-menu">
    <ul>
        <li>
            <a href="principal.php">
                <i class="
glyphicon glyphicon-list-alt glyphicon-2x"></i>
                <span class="nav-text">Voltar aos pedidos</span>
            </a>
        </li>
    </ul>
</nav>

<div class="container-fluid">
    <div class="wrap">
        <div id="duplicata">
            <div class="container" id="cabecalho">
                <br>
                <header class="cabecalho">
                    <div class="wrap-header">
                        <div class="imagem-header">
                            <img src="imagens/logo_ip.png" alt="Logo Varejão Irmaãos Patrocinio">
                            <h1>Pedidos - Varejão Irmãos Patrocinio</h1>
                        </div>
                    </div>
                </header>
            </div>

            <div class="container" id="produtos">
                <div class="title-padrao">
                    <h1 class="text-center">
                        Consulta de Pedidos
                    </h1>
                </div>

                <div id="allProducts">
                    <div class="separator"></div>
                    <form action="consulta.php" method="POST">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>Informe o ID do Pedido para consulta:</label>
                                <?php
                                if(isset($_SESSION['numero_vazio'])):
                                ?>
                                <div class="alert alert-warning alert-edit">
                                    <p>O campo de pesquisa não pode estar vazio, tente novamente !</p>
                                </div>
                                <?php
                                unset($_SESSION['numero_vazio']);
                                endif;
                                ?>


                                <?php
                                if(isset($_SESSION['pedido_vazio'])):
                                ?>
                                <div class="alert alert-danger alert-edit">
                                    <p>Número do pedido não encontrado, tente novamente !</p>
                                </div>
                                <?php
                                unset($_SESSION['pedido_vazio']);
                                endif;
                                ?>

                                <input type="number" class="form-control" id="pedido" name="pedido" autofocus>
                                <button type="submit" class="btn btn-primary btn-block" id="consulta-button">Consultar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer id="copy">
        <div>
            <p>Copyright &copy; 2019 - Varejão Irmãos Patrocinio</p>
            <p>Todos os direitos reservados.</p>
        </div>
    </footer>
</div>
</body>
<script>
</script>
</html>