<?php
session_start();
error_reporting(E_ERROR);
header('Content-Type: text/html; charset=utf-8');

require_once 'conexao.php';
require_once 'verifica_login.php';

$query_pedido = "SELECT id_pedido FROM pedido WHERE id_pedido = {$_POST['pedido']}";

$result = mysqli_query($connect, $query_pedido);

$row = mysqli_num_rows($result);

if ($row === 0) {
    $_SESSION['pedido_vazio'] = true;
    header('Location: consulta_pedidos.php');
    exit();
}


if (empty($_POST['pedido'])) {
    $_SESSION['numero_vazio'] = true;
    header('Location: consulta_pedidos.php');
    exit();
}

# Pedido Info
$query = "SELECT * FROM pedido WHERE id_pedido = {$_POST['pedido']}";
$pedido = mysqli_fetch_array(mysqli_query($connect, $query), MYSQLI_ASSOC);

# Itens de Pedido
$query = "SELECT * FROM pedido_item WHERE id_pedido = {$_POST['pedido']}";
$itens = mysqli_fetch_all(mysqli_query($connect, $query), MYSQLI_ASSOC);

?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Essas duas linhas são do Bootstrap, deixa a formatação mais 'bonita' -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Pedido N° <?= $_POST['pedido'] ?></title>
    <style>
        footer {
            width: 100%;
            position: relative;
            height: 100px;
            bottom: 0;
            left: 0;
        }

        #produtos {
            border-bottom: 1px solid #8d66b39e;
        }

        .main-menu {
            height: 85px;
        }

        .arq {
            display: flex;
            justify-content: center;
        }

        .arq button {
            border: none;
            background: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color:blue;
        }

        .arq button:hover {
            text-decoration: underline;
        }

        .arq .glyphicon {
            font-size: 30px;
        }

        .arq span {
            font-size: 20px;
        }
    </style>
</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<!--<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>-->
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/jquery.mask.js"></script>
<script src="js/bootstrap-select.js"></script>
<script src="js/script.js"></script>


<body onload="dataAtual()">

    <div class="area"></div>
    <nav class="main-menu">
        <ul>
            <li>
                <a href="consulta_pedidos.php">
                    <i class="glyphicon glyphicon-search glyphicon-2x"></i>
                    <span class="nav-text">Voltar à consulta</span>
                </a>
            </li>

            <li>
                <a href="principal.php">
                    <i class="glyphicon glyphicon-list-alt glyphicon-2x"></i>
                    <span class="nav-text">Voltar aos pedidos</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="container-fluid">
        <div class="wrap">
            <form action="pedido.php" method="GET" id="form-send">
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
                                Pedido N° <?= $_POST['pedido'] ?>
                            </h1>
                        </div>

                        <div class="arq">
                            <button onclick="gerarArq()" type="button" class="
                            glyphicon glyphicon-download-alt"><span>Gerar Arquivo</span></button>
                        </div>

                        <div id="allProducts">
                            <div class="separator"></div>
                            <p><b>ID do Pedido:</b> <?= $pedido['id_pedido'] ?></p>
                            <p><b>Nome Gerente / Fiscal: </b> <?= $pedido['nome_gerente_fiscal'] ?></p>
                            <p><b>ID do Fornecedor:</b> <?= $pedido['id_fornecedor'] ?></p>
                            <p><b>Nome do Fornecedor:</b> <?= $pedido['nome_fornecedor'] ?></p>
                            <p><b>CNPJ / CPF do Fornecedor:</b> <span id="doc"><?= $pedido['cnpj'] ?></span></p>
                            <p><b>Data do Pedido:</b> <?= date("d/m/Y H:i:s", strtotime($pedido['data'])) ?></p>
                            <p><b>Valor total do Pedido:</b> <?= $pedido['valor_total'] ?></p>
                            <p><b>Loja:</b> <?= $pedido['loja'] ?></p>

                            <div class="separator"></div>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Cod. Produto</th>
                                        <th>Produto</th>
                                        <th>V. Unitário</th>
                                        <th>Quantidade</th>
                                        <th>V. Total</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($itens as $item) { ?>
                                        <tr>
                                            <td><?= $item['item'] ?></td>
                                            <td><?= $item['id_produto'] ?></td>
                                            <td><?= $item['desc_produto'] ?></td>
                                            <td><?= $item['valor_un'] ?></td>
                                            <td><?= $item['quantidade'] ?></td>
                                            <td><?= $item['valor_total'] ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div id="pontilhado"></div>

        <footer id="copy">
            <div>
                <p>Copyright &copy; 2019 - Varejão Irmãos Patrocinio</p>
                <p>Todos os direitos reservados.</p>
            </div>
        </footer>
    </div>
    <script>
        var cnpj = $("#doc");
        $(cnpj).unmask();
        $(cnpj).mask("99.999.999/9999-99");

        var idPedido = <?php echo $pedido['id_pedido'] ?>;

        function gerarArq() {
            url = 'http://localhost/projeto_vale_vip/arquivo.php?idPedido=' + idPedido;
            window.location = url;
        }
    </script>
</body>

</html>